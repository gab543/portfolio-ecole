# Guide de Configuration Email - Étapes Finales

## ✅ Ce qui est déjà configuré :
- Provider: `mailjet_smtp` ✓
- Clés API Mailjet ✓
- Configuration SMTP ✓
- Connexion API ✓

## ❌ Ce qui reste à faire :

### 1. Remplacer les adresses email dans `configs/settings.php`

**Ligne 17-18 :** Remplacez les adresses génériques par vos vraies adresses :

```php
'from' => 'votre-email@domain.com',     // ← REMPLACEZ ICI
'admin' => 'votre-email@domain.com',    // ← REMPLACEZ ICI
```

### 2. Vérifier votre email dans Mailjet

1. Allez sur https://app.mailjet.com/
2. Menu "Account" → "Sender addresses"
3. Ajoutez votre adresse email si elle n'y est pas
4. Cliquez sur "Verify" et suivez les instructions

### 3. Tester

Une fois les emails configurés :
```bash
php diagnostic.php  # Vérifier que tout est OK
```

Puis testez via l'interface web : `/admin/request-access`

## 📧 Exemple de configuration valide :

```php
'from' => 'contact@monsite.com',
'admin' => 'admin@monsite.com',
```

**Important :** L'adresse `from` doit être vérifiée dans votre compte Mailjet !