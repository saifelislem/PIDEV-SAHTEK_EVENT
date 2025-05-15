 Lancement du projet
🧱 Backend
bash
Copier
Modifier
# Cloner le projet
git clone https://github.com/ton-utilisateur/shatek-event.git

# Accéder au dossier du projet
cd shatek-event

# Compiler les classes
javac -d bin src/**/*.java

# Lancer l'application (point d’entrée)
java -cp bin main.Main
Assure-toi que les fichiers .properties ou .config contiennent :

Les identifiants de la base de données

Les clés API (Google, email, etc.)

🎨 Frontend (JavaFX + Scene Builder)
Ouvre les fichiers .fxml avec Scene Builder

L'UI est connectée via les Controller.java

Lien entre les boutons, champs et méthodes dans Scene Builder (onAction, fx:id, etc.)

Utilise les services backend internes (ServiceUtilisateur, ServiceEvenement, etc.)

📁 Structure du projet
csharp
Copier
Modifier
shatek-event/
├── src/
│   ├── controllers/
│   ├── models/
│   ├── services/
│   ├── utils/
│   ├── views/            # fichiers FXML
│   └── main/             # point d'entrée (Main.java)
├── resources/            # fichiers de configuration, images, styles
├── bin/                  # fichiers compilés
└── README.md
