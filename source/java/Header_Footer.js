// header pour les pages du site
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

// footer pour les pages du site
const footerItems = [
    { href: "../../page/legal.html", label: "Mentions légales" },
    { href: "../../page/billeterie.php", label: "Billetterie" },
    { href: "../../page/reglement.php", label: "Règlement interne" },
    { type: "button", id: "openPopupBtn", label: "Contactez-nous" },
    { href: "../../page/login.php", label: "Connexion" }
];

const footer = document.getElementById("footer");

// Création du menu de footer
footerItems.forEach(item => {
    const li = document.createElement("li");
    li.classList.add("navFooter");

    if (item.type === "button") {
        // Bouton de contact pour ouvrir le formulaire en pop-up
        const button = document.createElement("button");
        button.id = item.id;
        button.textContent = item.label;
        button.classList.add("texteFooter");

        button.addEventListener("click", function() {
            document.getElementById("popupForm").style.display = "block";
            document.getElementById("overlay").style.display = "block";
        });

        li.appendChild(button);
    } else {
        const a = document.createElement("a");
        a.classList.add("texteFooter");
        a.href = item.href;
        a.textContent = item.label;
        li.appendChild(a);
    }

    footer.appendChild(li);
});

// Création dynamique du pop-up de contact
const overlay = document.createElement("div");
overlay.id = "overlay";
overlay.style.display = "none";
overlay.style.position = "fixed";
overlay.style.top = 0;
overlay.style.left = 0;
overlay.style.width = "100%";
overlay.style.height = "100%";
overlay.style.backgroundColor = "rgba(0, 0, 0, 0.5)";
overlay.style.zIndex = 999;

const popupForm = document.createElement("div");
popupForm.id = "popupForm";
popupForm.style.display = "none";
popupForm.style.position = "fixed";
popupForm.style.top = "50%";
popupForm.style.left = "50%";
popupForm.style.transform = "translate(-50%, -50%)";
popupForm.style.backgroundColor = "white";
popupForm.style.padding = "20px";
popupForm.style.boxShadow = "0 0 15px rgba(0, 0, 0, 0.3)";
popupForm.style.zIndex = 1000;
popupForm.style.width = "300px";

popupForm.innerHTML = `
    <span id="closePopupBtn" style="float: right; cursor: pointer; font-size: 1.2em;">&times;</span>
    <form id="contactForm" action="formContact.php" method="POST">
        <label for="pseudo">Pseudo :</label>
        <input type="text" id="pseudo" name="pseudo" required>
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>
        <label for="avis">Avis :</label>
        <textarea id="avis" name="avis" required></textarea>
        <button type="submit">Envoyer</button>
    </form>
    <div id="response"></div>
`;

// Ajout du pop-up et de l'overlay au corps de la page
document.body.appendChild(overlay);
document.body.appendChild(popupForm);

// Gestion de la fermeture du pop-up
document.getElementById("closePopupBtn").addEventListener("click", function() {
    popupForm.style.display = "none";
    overlay.style.display = "none";
});

// Envoi du formulaire via AJAX
document.getElementById("contactForm").addEventListener("submit", async function(event) {
    event.preventDefault();
    const formData = new FormData(this);

    const response = await fetch('formContact.php', {
        method: 'POST',
        body: formData
    });

    const result = await response.json();
    document.getElementById("response").innerText = result.success 
        ? "Votre message a été envoyé avec succès." 
        : result.error || "Une erreur est survenue.";
});