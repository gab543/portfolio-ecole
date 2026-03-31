# Services/Mail

Ce dossier contient toute la logique d'envoi de mail pour l application.

## Fichiers

- `Mailer.php`: couche d'abstraction d'envoi d'email.
  - `send(...)`
  - `sendDev(...)` (mode développement / mailhog)
  - `sendMailjet(...)` (API Mailjet)
  - `sendMailjetSmtp(...)` (SMTP Mailjet)
  - `sendPhpmailer(...)` (fallback général)

- `AccessRequestService.php`: fonctionnel pour les demandes d'accès admin.
  - `generateToken(...)`
  - `getConfirmLink(...)`
  - `buildRequestBody(...)`
  - `sendRequestNotification(...)`

## Configuration

Voir `configs/settings.php` section `mail`.
- `provider` : `mailhog`, `mailjet`, `mailjet_smtp`
- `from`, `admin`, `mailjet.api_key`, `mailjet.secret_key`, etc.

## Avantages

- séparation claire entre la logique business d'"accès" et le transport de mail
- facilité de tests unitaires/maintenabilité
- fallback local + API + SMTP

## Utilisation

Dans `AuthController::submitRequest`, on utilise :

```php
$mailer = new \Services\Mail\Mailer($mailConfig);
$accessRequestService = new \Services\Mail\AccessRequestService($mailer, $mailConfig);

$token = $accessRequestService->generateToken($email, $passwordHash);
$confirmLink = $accessRequestService->getConfirmLink($token);
$accessRequestService->sendRequestNotification($email, $message, $confirmLink);
```

Entrée: formulaire `views/admin/request_access.phtml`.

## Tests / debug

- Vérifier `logs/emails_dev.log` pour mailhog
- Valider dans interface Mailjet que la clé est active et que les adresses source/destination sont vérifiées.
