// En-tête pour les pages du site
const menuItems = [
    { href: "index.php", label: "Accueil" },
    { href: "service.php", label: "Services" },
    { href: "habitats.php", label: "Habitats" }
];
const currentPath = window.location.pathname;
const menu = document.getElementById("header");

// Ajout du logo
const logo = document.createElement("img");
logo.src = "../doc/photo/image_site/logo_arcadia_WEBP.webp";
logo.alt = "logo_du_site";
logo.classList.add("logoArcadia");
menu.appendChild(logo);

// Création du menu de navigation
menuItems.forEach(item => {
    const li = document.createElement("li");
    li.classList.add("navHeader");
    const a = document.createElement("a");
    a.classList.add("texteHeader");
    a.href = item.href;
    a.textContent = item.label;

    if (currentPath.includes(item.href.split("/").pop())) {
        a.classList.add("active");
    }

    li.appendChild(a);
    menu.appendChild(li);
});

// Pied de page pour les pages du site
const footerItems = [
    { type: "button", id: "openPopupBtn", label: "Contactez-nous" },
    { type: "button", id: "openConnexionBtn", label: "Connexion" },
];
const footer = document.getElementById("footer");

// Création du menu de pied de page
footerItems.forEach(item => {
    const li = document.createElement("li");
    li.classList.add("navFooter");

    if (item.type === "button") {
        const button = document.createElement("button");
        button.id = item.id;
        button.textContent = item.label;
        button.classList.add("texteFooter");

        if (item.id === "openPopupBtn") {
            button.addEventListener("click", () => {
                document.getElementById("contactOverlay").style.display = "block";
                document.getElementById("contactPopupForm").style.display = "block";
            });
        } else if (item.id === "openConnexionBtn") {
            button.addEventListener("click", () => {
                document.getElementById("connexionOverlay").style.display = "block";
                document.getElementById("connexionPopupForm").style.display = "block";
            });
        }

        li.appendChild(button);
    }
    footer.appendChild(li);
});

// Création du pop-up de Contact
const contactOverlay = document.createElement("div");
contactOverlay.id = "contactOverlay";
contactOverlay.style.display = "none";
contactOverlay.style.position = "fixed";
contactOverlay.style.top = 0;
contactOverlay.style.left = 0;
contactOverlay.style.width = "100%";
contactOverlay.style.height = "100%";
contactOverlay.style.backgroundColor = "rgba(0, 0, 0, 0.5)";
contactOverlay.style.zIndex = 999;

const contactPopupForm = document.createElement("div");
contactPopupForm.id = "contactPopupForm";
contactPopupForm.style.display = "none";
contactPopupForm.style.position = "fixed";
contactPopupForm.style.top = "50%";
contactPopupForm.style.left = "50%";
contactPopupForm.style.transform = "translate(-50%, -50%)";
contactPopupForm.style.backgroundColor = "white";
contactPopupForm.style.padding = "20px";
contactPopupForm.style.boxShadow = "0 0 15px rgba(0, 0, 0, 0.3)";
contactPopupForm.style.zIndex = 1000;
contactPopupForm.style.width = "300px";

contactPopupForm.innerHTML = `
    <span id="closeContactPopupBtn" style="float: right; cursor: pointer; font-size: 1.2em;">&times;</span>
    <form id="contactForm" action="formContact.php" method="POST">
        <label for="pseudo">Pseudo :</label>
        <input type="text" id="pseudo" name="pseudo" required>
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>
        <label for="avis">Avis :</label>
        <textarea id="avis" name="avis" required></textarea>
        <button type="submit">Envoyer</button>
    </form>
    <div id="responseContact"></div>
`;

// Ajout du pop-up de Contact et de son overlay au corps de la page
document.body.appendChild(contactOverlay);
document.body.appendChild(contactPopupForm);

// Gestion de la fermeture du pop-up de Contact
document.getElementById("closeContactPopupBtn").addEventListener("click", function() {
    contactPopupForm.style.display = "none";
    contactOverlay.style.display = "none";
});

// Création du pop-up de Connexion
const connexionOverlay = document.createElement("div");
connexionOverlay.id = "connexionOverlay";
connexionOverlay.style.display = "none";
connexionOverlay.style.position = "fixed";
connexionOverlay.style.top = 0;
connexionOverlay.style.left = 0;
connexionOverlay.style.width = "100%";
connexionOverlay.style.height = "100%";
connexionOverlay.style.backgroundColor = "rgba(0, 0, 0, 0.5)";
connexionOverlay.style.zIndex = 999;

const connexionPopupForm = document.createElement("div");
connexionPopupForm.id = "connexionPopupForm";
connexionPopupForm.style.display = "none";
connexionPopupForm.style.position = "fixed";
connexionPopupForm.style.top = "50%";
connexionPopupForm.style.left = "50%";
connexionPopupForm.style.transform = "translate(-50%, -50%)";
connexionPopupForm.style.backgroundColor = "white";
connexionPopupForm.style.padding = "20px";
connexionPopupForm.style.boxShadow = "0 0 15px rgba(0, 0, 0, 0.3)";
connexionPopupForm.style.zIndex = 1000;
connexionPopupForm.style.width = "300px";

connexionPopupForm.innerHTML = `
    <span id="closeConnexionPopupBtn" style="float: right; cursor: pointer; font-size: 1.2em;">&times;</span>
    <form id="connexionForm" action="connexion.php" method="POST">
        <label for="mailConnexion">Pseudo :</label>
        <input type="text" id="mailConnexion" name="mailConnexion" required>
        <label for="motDePasse">Mot de passe :</label>
        <input type="password" id="motDePasse" name="motDePasse" required>
        <button type="submit">Connexion</button>
    </form>
    <div id="responseConnexion"></div>
`;

// Ajout du pop-up de Connexion et de son overlay au corps de la page
document.body.appendChild(connexionOverlay);
document.body.appendChild(connexionPopupForm);

// Gestion de la fermeture du pop-up de Connexion
document.getElementById("closeConnexionPopupBtn").addEventListener("click", function() {
    connexionPopupForm.style.display = "none";
    connexionOverlay.style.display = "none";
});