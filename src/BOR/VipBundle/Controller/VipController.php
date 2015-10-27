<?php

namespace BOR\VipBundle\Controller;

use BOR\VipBundle\Entity\Purchase;
use BOR\VipBundle\Form\PurchaseType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Braintree_ClientToken;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Class VipController
 *
 * @package BOR\VipBundle\Controller
 */
class VipController extends Controller
{
    /**
     * @Route("/achat", name="bor_vip_index")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        /*
        A garder pour c'en resservir : pour les tries
        $entities = $em->getRepository('BORAdvertBundle:Advert')->filterFind();
        */
        $entities = $em->getRepository('BORVipBundle:Package')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * @param integer $productName
     *
     * @return Array
     *
     * @throws \CometCult\BraintreeBundle\Exception\InvalidServiceException
     * @Route("/achat/{productName}", name="bor_purchase_create")
     * @Template("")
     *
     */
    public function checkoutAction($productName)
    {
        $request = $this->getRequest();
        $message = array();
        $clientToken = "";
        if ($this->get('security.context')->isGranted('ROLE_USER')) {

            $em = $this->getDoctrine()->getManager();

            //Récupération du produit pour l'afficher en front
            $package = $em->getRepository('BORVipBundle:Package')->findOneByUniqueName($productName);

            if (!$package) {
                throw $this->createNotFoundException('Unable to find the package entity.');
            }
            //Récupération du user en cours pour les informations customer
            $user = $this->getUser();

            //Instanciation de braintree pour récupérer les services
            $factory = $this->get('comet_cult_braintree.factory');

            //Création du customer sur braintree
            $customerService = $factory->get("Customer");
            $braintreeId = $user->getBraintreeId();

            if ($braintreeId != "") {
                $customerService->find($braintreeId);
                $clientToken = Braintree_ClientToken::generate(array(
                    "customerId" => $braintreeId
                ));
                $customerId = $braintreeId;
            } else {
                $customer = $customerService->create(array(
                    'firstName' => $user->getFirstname(),
                    'lastName' => $user->getlastname(),
                    'email' => $user->getEmail(),
                    'phone' => $user->getPhone(),
                ));


                if ($customer->success) {
                    $customerId = $customer->customer->id;
                    $user->setBraintreeId($customerId);
                    $clientToken = Braintree_ClientToken::generate(array(
                        "customerId" => $braintreeId
                    ));
                } else {
                    foreach ($customer->errors->deepAll() as $error) {
                        $message[] = array(
                            'code' => $error->code,
                            'message' => $error->message
                            );
                    }
                }

            }
            //Récupération du service braintree

            $entity = new Purchase();
            //Création du formulaire
            $form = $this->createForm(new PurchaseType(), $entity, array(
                'action' => $this->generateUrl('bor_purchase_create', array("productName" => $productName)),
                'method' => 'POST',
            ));

            $form->handleRequest($request);
            // Dès que le formulaire est soumis :
            if ($form->isValid()) {

                $saleService = $factory->get('Transaction');

                $sale = $saleService->sale(array(
                    "customerId" => $customerId,
                    "amount" => $package->getPrice(),
                    "paymentMethodNonce" => $form->get('paymentMethod')->getData(),
                    "taxAmount" => $package->getTax()
                ));


                if ($sale->success) {
                    $purchase = new Purchase();
                    //Set de l'entité purchase pour enregistrer la vente
                    $purchase->setUser($user);
                    $purchase->setPackage($package);
                    $purchase->setInvoiceCity(" ");
                    $purchase->setInvoiceFirstname($user->getFirstname());
                    $purchase->setInvoicelastname($user->getLastname());
                    $purchase->setInvoiceCity(" ");
                    $purchase->setInvoiceCountry("France");
                    $purchase->setInvoiceStreet(" ");
                    $purchase->setInvoiceZipCode(" ");
                    $purchase->setPrice($package->getPrice());
                    $purchase->setPriceIva($package->getPrice() + $package->getTax());
                    $purchase->setTaxAmount($package->getTax());
                    $purchase->setTransactionId($sale->transaction->id);
                    $purchase->setOrderNumber(" ");

                    //Mise à jour de la date d'abonnement de l'utilisateur.
                    $user = $this->setSubscriptionTime($user, $package);
                    //Persiste de l'entité purchase
                    //Ajout du temps à l'utilisateur
                    $this->get('bor_core.gamification')->gammificationAction($user, 'achatAbonnement');
                    $em->persist($user);
                    $em->persist($purchase);

                    $em->flush();

                    return $this->redirect($this->generateUrl('bor_core_index', array()));

                } else {
                    $message[] = array(
                        'code' => 500,
                        'message' => $sale->message
                    );
                }
            }

            return array("package" => $package,
                "token" => $clientToken,
                'form' => $form->createView(),
                'message' => $message
            );
        } else {
            return $this->redirect($this->generateUrl('fos_user_security_login', array()));
        }
    }

    /**
     * @param \BOR\UserBundle\Entity\User     $user
     * @param \BOR\VipBundle\Entity\Package   $package
     *
     * @return \BOR\UserBundle\Entity\User $user
     */
    private function setSubscriptionTime($user, $package)
    {
        if (isset($user) && isset($package)) {
            $subscribeEnd = $user->getSubscribeEnd();
            $date = new \DateTime();

            //Si l'abonnement est en cours, on ajouter des jours. Sinon on démarrer l'abonnement à partir de aujourd'hui.
            if ($subscribeEnd < $date) {
                $subscribeEnd = $date->modify('+' . $package->getDays() . ' days');
            } else {
                $subscribeEnd = $subscribeEnd->add(new \DateInterval('P'.$package->getDays().'D'));
            }

            //C'est dégueu mais c'est le seul moyen !!
            $subscribeEnd = $subscribeEnd->format("Y-m-d");
            $subscribeFormattedDate = new \DateTime($subscribeEnd);
            $user->setSubscribeEnd($subscribeFormattedDate);

            return $user;
        }

        return false;
    }

    /**
     * Liste tous les achat de l'utilisateurs.
     *
     * @return array
     *
     * @Route("/mon-profil/mes-achats/", name="bor_purchase_me")
     * @Template("BORVipBundle:Vip:myPurchase.html.twig")
     */
    public function myPurchaseAction()
    {

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('BORVipBundle:Purchase');

        $entities = $repo->findByUser($this->getUser());

        return array(
            'entities'      => $entities,
        );
    }
}
