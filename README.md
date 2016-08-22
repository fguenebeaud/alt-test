ALTIMA - Test d'entrée
========================

Ceci représente le test de développement pour Altima, réalisé par Florent GUENEBEAUD

- Durée du projet:  9h (une demie journée + une journée pour l'installation de l'environnement)
- Login après installation du projet : username: admin / password: test   rôle: ROLE_ADMIN
- Contenu du projet:

  * Back Office permettant de gérer les newsletter ( Ajout / Suppression / Liste ), les contact ( Ajout / Suppression / Vue / liste ) et les news ( Ajout / Suppression / Edition / liste ) (route /admin)

  * Front office fourni, avec les formulaire de contact et newsletter dynamique ainsi que la partie Connexion en haut à droite qui permet de se connecter au back office. les news sont elles aussi dynamique dynamique

  * Jeux de tests disponible

    IMPORTANT : sur le front, les flashmessages ( messages succès ) apparaissent en haut de la page, au dessus de Altima

  Pourquoi avoir choisi Symfony ?

  Le framework est complet et apporte de nombreux éléments dans sa librairie, outre les bundles tel que les dataFixtures qui permettent de créer un jeu de données de test,
  ou bien FOSUser qui après un peu de temps pour configurer la connexion est utilisable assez simplement sans ligne de code supplémentaire.
  Par ailleurs, la gestion des formulaire ( et leurs validations ) est géré simplement avec les annotations fourni par Symfony , ce qui en soit est plus rapide. Enfin on utilise également la puissance de Doctrine avec ses entités mappé directement grâce aux classes PHP (via annotation)

  Concernant la lisibilité, le framework est un framework MVC ( Modèle , Vue , Contrôleur ), ce qui permet de séparé ces trois couches et ainsi retrouver chaque élément dans un dossier différent ( les contrôleurs dans Controller etc ) cela permet une certaine maintenabilité et de débuguer plus rapidement

  Le framework Symfony permet donc de gagner du temps et en sécurité (les formulaires utilisent des tokens spécifiques )

Installation
--------------

Prérequis

  * composer.phar

Commandes

  * php composer.phar install

  * php app/console doctrine:database:create

  * php app/console doctrine:schema:update --force

  * php app/console doctrine:fixtures:load