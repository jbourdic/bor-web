Feature:
  Register

Scenario:
  Given I am on "/inscription/"
  When I fill in "fos_user_registration_form_civility" with "Monsieur"
  And I fill in "fos_user_registration_form_firstname" with "John"
  And I fill in "fos_user_registration_form_lastname" with "Doe"
  And I fill in "fos_user_registration_form_phone" with "0606060606"
  And I fill in "fos_user_registration_form_zipCode" with "69000"
  And I fill in "fos_user_registration_form_email" with a random email
  And I fill in "fos_user_registration_form_plainPassword_first" with "johndoe"
  And I fill in "fos_user_registration_form_plainPassword_second" with "johndoe"
  And I press "Enregistrer"
  Then I should be on "/"
  Then the response status code should be 200
  Then the response should contain "John"
