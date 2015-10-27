<?php

namespace BOR\CoreBundle\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Command\Guzzle\Description;
use GuzzleHttp\Command\Guzzle\GuzzleClient;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class MoocProvider
 *
 * @package BOR\CoreBundle\Service
 */
class MoocProvider extends GuzzleClient
{
    protected $enterUrl;
    protected $sharedKey;

    /**
     * @param string $enterUrl
     * @param string $loginUrl
     * @param string $sharedKey
     * @param array $mooc
     * @param array $config
     */
    public function __construct($enterUrl, $loginUrl, $sharedKey, array $mooc = [], array $config = [])
    {
        $this->enterUrl = $enterUrl;
        $this->sharedKey = $sharedKey;
        $this->loginUrl = $loginUrl;

        $resolver = new OptionsResolver();
        $this->configureOptionResolver($resolver);

        // validation des paramètres
        $options = $resolver->resolve($mooc);

        // initialisation du client standard Guzzle
        $client = new Client([
            "defaults" => [
                // headers attendus par MaTpe
                "headers" => [
                    "Content-type" => "application/json; charset=utf-8"
                ],
            ],
            "base_url" => [
                $options["base_url"],
                []
            ]
        ]);

        // définition des requètes supportées par notre service
        $description = new Description([
            "name" => 'MaTpe',
            "description" => "Exemple d'API MaTpe avec Guzzle",
            // list des opérations supportées
            "operations" => [
                // pour commencer, une simple récupération de la liste des clients
                "getUserCourses" => [
                    "httpMethod" => "GET",
                    // l'uri est ajoutée à notre base_url définie par défaut
                    "uri"=> "api/user/courses",
                    // la réponse attendue sera traitée avec le model jsonResponse,
                    // déclaré plus bas dans "models"
                    "responseModel" => "jsonResponse",
                    "parameters" => [
                        "userId" => [
                            "required" => true,
                            "location" => "query"
                        ],
                        "sharedKey" => [
                            "required" => true,
                            "location" => "query"
                        ]
                    ],
                    // par défaut tout paramètre additionnel passé à cette requète
                    // sera envoyé dans le query_string de l'url appelée
                    "additionalParameters" => [
                        "location" => "query"
                    ]
                ],
                // récupération d'un client à partir de son id
                "getUserChallenges" => [
                    "httpMethod" => "GET",
                    // pour la récupération d'un client spécifique l'id est dans l'uri
                    "uri" => "api/user/challenges",
                    "responseModel" => "jsonResponse",
                    // on spécifie ici que le paramètre id est obligatoire et se trouve dans l'uri
                    "parameters" => [
                        "userId" => [
                            "required" => true,
                            "location" => "query"
                        ],
                        "sharedKey" => [
                            "required" => true,
                            "location" => "query"
                        ]
                    ],
                    // par défaut tout paramètre additionnel passé à cette requète
                    // sera envoyé dans le query_string de l'url appelée
                    "additionalParameters" => [
                        "location" => "query"
                    ]
                ]
            ],
            // les models permettent de définir le traitement appliqué aux réponses de l'API
            // on spécifie ici que l'on veut un objet php à partir du json contenu dans la réponse
            "models" => [
                "jsonResponse" => [
                    "type" => "object",
                    "additionalProperties" => [
                        "location" => "json"
                    ]
                ]
            ]
        ]);

        parent::__construct($client, $description, $config);
    }


    /**
     * @param OptionsResolverInterface $resolver
     */
    protected function configureOptionResolver(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults([
                'base_url' => $this->getURL(),
            ]);
    }

    /**
     * Fournit l'URL du Mooc
     *
     * @return string
     */
    public function getURL()
    {
        return $this->enterUrl;
    }

    /**
     * Fournit l'URL du Mooc pour la connexion
     *
     * @return string
     */
    public function getLoginURL()
    {
        return $this->loginUrl;
    }

    /**
     * Fournit la clé partagée du Mooc
     *
     * @return string
     */
    public function getSharedKey()
    {
        return $this->sharedKey;
    }
}
