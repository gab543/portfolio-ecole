# Validation des Adresses Email dans Mailjet

## ⚠️ Important : Validation Requise

Pour recevoir de vrais emails, vos adresses Gmail doivent être **validées** dans Mailjet.

## 📋 Adresses à Valider

- **Expéditeur** : `gabcab1002@gmail.com`
- **Destinataire** : `gabriel.caboche@gmail.com`

## 🚀 Étapes de Validation

### 1. Accéder au Dashboard Mailjet
- Allez sur : https://app.mailjet.com/
- Connectez-vous à votre compte

### 2. Aller dans Sender Addresses
- Menu latéral : **Account** → **Sender addresses**
- Ou directement : https://app.mailjet.com/account/sender

### 3. Vérifier/Ajouter les Adresses

Pour chaque adresse (`gabcab1002@gmail.com` et `gabriel.caboche@gmail.com`) :

#### Si l'adresse n'existe pas :
- Cliquez sur **"Add a sender"**
- Entrez l'adresse email
- Cliquez sur **"Add"**

#### Si l'adresse existe mais n'est pas validée :
- Trouvez l'adresse dans la liste
- Si le statut est "Not verified" (rouge)
- Cliquez sur **"Verify"**

### 4. Processus de Validation

Pour chaque adresse à valider :

1. **Cliquez sur "Verify"**
2. **Choisissez la méthode** :
   - ✅ **Email validation** (recommandé) : Mailjet envoie un email avec un lien
   - ⚠️ **DNS validation** : Plus complexe, nécessite accès au DNS

3. **Pour l'email validation** :
   - Vérifiez votre boîte Gmail
   - Cherchez l'email de Mailjet
   - Cliquez sur le lien de validation

4. **Statut devient "Verified"** (vert) ✅

## 🔍 Vérifier le Statut

- **Vert** ✅ : Validé - les emails peuvent être envoyés
- **Rouge** ❌ : Non validé - les emails seront rejetés
- **Orange** ⏳ : En attente de validation

## 📧 Test Final

Une fois les adresses validées :

1. **Testez l'envoi** :
   ```bash
   # Via interface web
   http://localhost:8000/admin/request-access
   ```

2. **Vérifiez Gmail** :
   - Boîte de réception
   - Dossier Spam/Courrier indésirable

## 🆘 Si les Emails N'arrivent Pas

### 1. Vérifiez le Statut dans Mailjet
- Toutes les adresses doivent être "Verified"

### 2. Vérifiez les Logs Mailjet
- Dashboard → **Activity** → **Messages**
- Cherchez les erreurs de livraison

### 3. Vérifiez Gmail
- **Boîte de réception**
- **Spam/Courrier indésirable**
- **Tous les emails** (recherche par expéditeur)

### 4. Test Simple
```bash
# Test direct depuis terminal
php -r "
require 'vendor/autoload.php';
\$dotenv = Dotenv\Dotenv::createImmutable(__DIR__); \$dotenv->load();
\$mj = new Mailjet\Client(getenv('API_MAIL'), getenv('SECRET_API_MAIL'), true, ['version' => 'v3']);
\$response = \$mj->get(Mailjet\Resources::\$Apikey);
echo \$response->success() ? '✅ API OK' : '❌ API KO';
"
```

## 📞 Support Mailjet

Si vous avez des problèmes :
- **Documentation** : https://dev.mailjet.com/email/guides/sender-address/
- **Support** : https://app.mailjet.com/support

## ✅ Résumé

1. **Validez** `gabcab1002@gmail.com` (expéditeur)
2. **Validez** `gabriel.caboche@gmail.com` (destinataire)
3. **Testez** via l'interface web
4. **Vérifiez** votre boîte Gmail

**Une fois validé, vous recevrez tous les emails dans Gmail !** 📧✨