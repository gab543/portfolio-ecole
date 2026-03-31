# Guide MailHog - Développement Local

## ❓ MailHog : Qu'est-ce que c'est ?

**MailHog** est un outil de développement qui **intercepte les emails SMTP** au lieu de les envoyer réellement. Il crée un serveur SMTP local qui "attrape" tous les emails et les affiche dans une interface web.

## ✅ Avantages de MailHog

- ✅ **Pas d'envoi réel** : Aucun email n'est envoyé vers des adresses externes
- ✅ **Interface web** : Visualisez facilement tous les emails envoyés
- ✅ **Contenu complet** : Voyez le HTML, les pièces jointes, etc.
- ✅ **API REST** : Récupérez les emails via API si besoin
- ✅ **Gratuit et open-source**

## ❌ Limitations de MailHog

- ❌ **Localhost uniquement** : Ne fonctionne que sur votre machine
- ❌ **Pas d'envoi réel** : Impossible d'envoyer des emails vers Gmail, etc.
- ❌ **Pas pour la production** : Uniquement pour le développement

## 🛠️ Installation de MailHog

### Option 1 : Via Go (Recommandé)
```bash
# Installer Go si pas déjà fait
# Puis :
go install github.com/mailhog/MailHog@latest

# Lancer MailHog
~/go/bin/MailHog
```

### Option 2 : Via Docker
```bash
docker run -d -p 1025:1025 -p 8025:8025 mailhog/mailhog
```

### Option 3 : Binaire Pré-compilé
1. Téléchargez depuis : https://github.com/mailhog/MailHog/releases
2. Extrayez et lancez : `./MailHog`

## 🚀 Utilisation

### 1. Lancer MailHog
```bash
# Depuis le dossier où se trouve MailHog
./MailHog
```

### 2. Configuration dans votre projet
```env
# Dans .env
MAIL_PROVIDER = "mailhog"
```

### 3. Interface Web
- **URL** : http://localhost:8025
- **SMTP** : localhost:1025 (configuré automatiquement)

### 4. Tester
1. Allez sur : `http://localhost:8000/admin/request-access`
2. Remplissez et envoyez le formulaire
3. Vérifiez : `http://localhost:8025`

## 📧 Alternatives si vous voulez des vrais emails

### 1. Mailjet SMTP (Comme configuré précédemment)
```env
MAIL_PROVIDER = "mailjet_smtp"
```
- ✅ Envoi réel vers Gmail, etc.
- ✅ Fonctionne en développement local
- ⚠️ Nécessite validation des adresses dans Mailjet

### 2. Mailtrap
- Service en ligne gratuit pour développement
- Interface web pour voir les emails
- Configuration SMTP simple

### 3. SendGrid (gratuit pour développement)
- Service SMTP professionnel
- 100 emails/jour gratuits
- Configuration similaire à Mailjet

## 🔄 Changer entre les modes

### Mode MailHog (pas d'envoi réel) :
```env
MAIL_PROVIDER = "mailhog"
```

### Mode Mailjet (envoi réel) :
```env
MAIL_PROVIDER = "mailjet_smtp"
```

## 📋 Checklist Installation MailHog

- [ ] Télécharger MailHog
- [ ] Lancer `./MailHog` (ou `mailhog` selon l'installation)
- [ ] Vérifier http://localhost:8025
- [ ] Configurer `MAIL_PROVIDER = "mailhog"` dans .env
- [ ] Tester via l'interface web du portfolio

## 🎯 Quand utiliser MailHog ?

- **Développement pur** : Quand vous ne voulez pas envoyer d'emails
- **Tests automatisés** : Pour vérifier le contenu des emails
- **Démonstration** : Montrer l'application sans spammer
- **Debugging** : Voir exactement ce qui est envoyé

**MailHog est parfait pour le développement local quand vous ne voulez pas d'emails réels !** 🐗📧