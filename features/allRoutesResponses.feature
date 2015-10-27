Feature:
  Test the status code and a keyword on every route
  Test redirection when accessing an account restricted page while not logged in

Scenario Outline:
  When I am on "<page>"
  Then I should be on "<page>"
  Then the response status code should be 200
  Then the response should contain "<contain>"

Examples:
  | page                                     | contain |
  | /                                        | Blabla  |
  | /contact/                                | Blabla  |
  | /concept                                 | Blabla  |
  | /cgu                                     | Blabla  |
  | /annonce/                                | Blabla  |
  | /article/                                | Blabla  |
  | /article/news                            | Blabla  |
  | /article/renover                         | Blabla  |
  | /inscription/                            | Blabla  |
  | /resetting/request                       | Blabla  |
  | /blockuser/new                           | Blabla  |
  | /experts                                 | Blabla  |
  | /adherer                                 | Blabla  |
  | /api/mobile/open/user/user@user.com      | user    |
  | /partenaire                              | Blabla  |

Scenario Outline:
  When I go to "<page>"
  Then I should be on "/login"
  Then the response status code should be 200

Examples:
  | page                     |
  | /mon-profil              |
  | /mon-profil/mes-favoris/ |
  | /mon-profil/mes-annonces/ |
  | /mon-profil/mes-articles/ |
  | /mon-profil/ma-galerie/  |
  | /mon-profil/profil/      |

  | /annonce/create          |
  | /blockpost/create        |
  | /article/create          |
  | /user/disable            |

  | /admin/partenaire        |
  | /admin/partenaire/new    |
  | /admin/annonce/          |
  | /admin/annonce/create    |
  | /admin/article/          |
  | /admin/article/news      |
  | /admin/article/renovate  |
  | /admin/article/create    |
  | /admin/galerie/          |
  | /admin/user              |
  | /admin/user/new          |
  | /admin/action/           |
  | /admin/action/new        |
  | /admin/level/            |
  | /admin/level/new         |
  | /admin/qcmpoint/         |
  | /admin/qcmpoint/new      |
  | /admin/blockpost         |
  | /admin/slider/           |
  | /admin/slider/new        |
  | /admin/blockadvert       |
  | /admin/blockuser         |

Scenario Outline:
  Given I am logged in as "user"
  When I go to "<page>"
  Then I should be on "<page>"
  Then the response status code should be 200

Examples:
  | page                      |
  | /mon-profil               |
  | /mon-profil/mes-favoris/  |
  | /mon-profil/mes-annonces/ |
  | /mon-profil/mes-articles/ |
  | /mon-profil/ma-galerie/   |
  | /mon-profil/profil/       |

  | /annonce/create           |

Scenario Outline:
  Given I am logged in as "admin"
  When I go to "<page>"
  Then I should be on "<page>"
  Then the response status code should be 200

Examples:
  | page                     |
  | /admin/partenaire        |
  | /admin/partenaire/new    |
  | /admin/annonce/          |
  | /admin/annonce/create    |
  | /admin/article/          |
  | /admin/article/news      |
  | /admin/article/renovate  |
  | /admin/article/create    |
  | /admin/galerie/          |
  | /admin/user              |
  | /admin/user/new          |
  | /admin/action/           |
  | /admin/action/new        |
  | /admin/level/            |
  | /admin/level/new         |
  | /admin/qcmpoint/         |
  | /admin/qcmpoint/new      |
  | /admin/blockpost         |
  | /admin/slider/           |
  | /admin/slider/new        |
  | /admin/blockadvert       |
  | /admin/blockuser         |
