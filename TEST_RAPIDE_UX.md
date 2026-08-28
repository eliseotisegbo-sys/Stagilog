# 🧪 TEST RAPIDE UX - 3 CORRECTIONS

## ⏱️ Durée: 3 minutes

---

## ✅ TEST 1: Durée en Jours (30 secondes)

### Action
1. Créer nouveau dossier de stage
2. Sélectionner:
   - Date début: **1er janvier 2027**
   - Date fin: **15 janvier 2027**

### ✓ Résultat Attendu
```
Badge bleu affiché:
"Durée estimée: 14 jours (~1 mois / 2 semaines)"
```

### ❌ Si Incorrect
```
"Durée estimée: 13.999999999988 jours"
→ Rafraîchir la page (Ctrl+Shift+R)
```

---

## ✅ TEST 2: Calendrier Date Naissance (1 minute)

### Action
1. Dans le formulaire dossier, ajouter un étudiant
2. Cliquer sur **"Date de naissance"**
3. Observer le calendrier qui s'ouvre

### ✓ Vérifications Visuelles

#### Taille et Layout
```
[✓] Calendrier plus grand (340px)
[✓] Flèche < visible en haut gauche
[✓] Dropdown mois large (min 140px)
[✓] Année dans boîte séparée (70px)
```

#### Boutons UP/DOWN (FOCUS PRINCIPAL)
```
[✓] 2 boutons visibles à droite de l'année
[✓] Taille: environ 18px × 16px
[✓] Background: blanc dégradé
[✓] Bordure: grise visible
[✓] Forme: rectangles arrondis
```

#### Test Interactif
```
1. Survoler bouton UP (▲)
   ✓ Devient BLEU VIF instantanément
   ✓ Animation scale (1.15)
   ✓ Ombre bleue lumineuse

2. Cliquer plusieurs fois UP/DOWN
   ✓ Navigation rapide année par année
   ✓ Feedback visuel à chaque clic
   
3. Taper "1995" dans l'input année
   ✓ Calendrier change immédiatement
   ✓ Affiche janvier 1995
```

### 📸 Comparaison Visuelle

**AVANT (❌):**
- Boutons UP/DOWN petits et transparents
- Difficile à voir et à cliquer

**APRÈS (✅):**
```
┌─────────────────────────────┐
│  <  │ Janvier  │  1995  │▲│ │
│                          │▼│ │
└─────────────────────────────┘
        ↑          ↑      ↑↑
     Flèche    Dropdown  Boutons
                        TRÈS visibles!
```

---

## ✅ TEST 3: Scrollbars (1 minute 30)

### Action 1: Mode Clair
1. Ouvrir une page avec scrollbar (Dashboard ou Dossiers)
2. Observer la scrollbar à droite

### ✓ Vérifications
```
[✓] Width: 10px (plus large qu'avant)
[✓] Rail: dégradé bleu clair avec bordure
[✓] Curseur: gris-bleu avec bordure blanche
```

### Action 2: Survol
Passer la souris sur le curseur de scrollbar

### ✓ Effet Attendu
```
TRANSFORMATION IMMÉDIATE:
- Curseur devient BLEU VIF (#3B82F6)
- Ombre bleue lumineuse (glow)
- Légère augmentation de taille (scale 1.1)
- Animation fluide
```

### Action 3: Mode Sombre
1. Activer mode sombre
2. Observer scrollbar

### ✓ Vérifications Mode Sombre
```
[✓] Rail: bleu très foncé visible
[✓] Curseur: gris foncé (#475569)
[✓] Hover: bleu clair (#60A5FA) avec glow
```

### 📸 Comparaison Visuelle

**AVANT (❌):**
```
│ │  ← 6px, grise unie, discrète
```

**APRÈS (✅):**
```
║███║  ← 10px, dégradé élégant

HOVER:
║███║  ← BLEU VIF + ombre lumineuse!
     (( ))  ← glow visible
```

---

## ✅ VALIDATION FINALE

### Checklist Rapide
```
[✓] Durée: 14 jours (pas 13.99...)
[✓] Boutons UP/DOWN: grands et bleus au survol
[✓] Scrollbar: 10px avec effet bleu au hover
```

### Si Tout Fonctionne
```
✅ SUCCÈS - Les 3 corrections UX sont actives!
→ Retour à l'utilisation normale
```

### Si Problème
```
1. Rafraîchir avec Ctrl+Shift+R
2. Vider cache navigateur
3. Vérifier console (F12) pour erreurs
```

---

## 🎯 POINTS D'ATTENTION

### Calendrier Date Naissance
**Le plus important:** Les boutons UP/DOWN doivent être:
- ✅ Clairement visibles (18×16px)
- ✅ Réactifs au survol (bleu vif)
- ✅ Faciles à cliquer

### Scrollbar
**Le plus important:** Au survol, elle doit:
- ✅ Devenir bleue instantanément
- ✅ Avoir une ombre lumineuse visible
- ✅ Donner un feedback visuel fort

---

**Durée totale:** ~3 minutes  
**Critères de succès:** 3/3 corrections visibles et fonctionnelles
