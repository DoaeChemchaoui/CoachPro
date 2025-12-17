CREATE DATABASE plateforme_sport;
USE plateforme_sport;

CREATE TABLE role (
    id_role INT AUTO_INCREMENT PRIMARY KEY,
    nom_role VARCHAR(50) NOT NULL
);


CREATE TABLE sportif (
    id_sportif INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50),
    prenom VARCHAR(50),
    email VARCHAR(100) UNIQUE,
    mot_de_passe VARCHAR(255),
    telephone VARCHAR(20),
    date_naissance DATE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE coach (
    id_coach INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50),
    prenom VARCHAR(50),
    email VARCHAR(100) UNIQUE,
    mot_de_passe VARCHAR(255),
    telephone VARCHAR(20),
    biographie TEXT,
    photo VARCHAR(255),
    disciplines VARCHAR(255),
    niveau VARCHAR(50),
    annees_experience INT,
    certifications VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE disponibilite (
    id_disponibilite INT AUTO_INCREMENT PRIMARY KEY,
    id_coach INT,
    date DATE,
    heure_debut TIME,
    heure_fin TIME,
    etat VARCHAR(20) DEFAULT 'libre',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_coach) REFERENCES coach(id_coach) ON DELETE CASCADE
);


CREATE TABLE reservation (
    id_reservation INT AUTO_INCREMENT PRIMARY KEY,
    id_sportif INT,
    id_coach INT,
    id_disponibilite INT,
    statut VARCHAR(20) DEFAULT 'en attente',
    date_reservation DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_sportif) REFERENCES sportif(id_sportif) ON DELETE CASCADE,
    FOREIGN KEY (id_coach) REFERENCES coach(id_coach) ON DELETE CASCADE,
    FOREIGN KEY (id_disponibilite) REFERENCES disponibilite(id_disponibilite) ON DELETE CASCADE
);


-- Lister toutes les réservations d’un coach avec infos du sportif et de la disponibilité
SELECT 
    r.id_reservation,
    s.nom AS nom_sportif,
    s.prenom AS prenom_sportif,
    s.email AS email_sportif,
    d.date AS date_seance,
    d.heure_debut,
    d.heure_fin,
    r.statut
FROM reservation r
JOIN sportif s ON r.id_sportif = s.id_sportif
JOIN disponibilite d ON r.id_disponibilite = d.id_disponibilite
WHERE r.id_coach = 1;  -- remplace 1 par l'id du coach

--  Lister toutes les réservations d’un sportif avec infos du coach
SELECT 
    r.id_reservation,
    c.nom AS nom_coach,
    c.prenom AS prenom_coach,
    c.disciplines,
    d.date AS date_seance,
    d.heure_debut,
    d.heure_fin,
    r.statut
FROM reservation r
JOIN coach c ON r.id_coach = c.id_coach
JOIN disponibilite d ON r.id_disponibilite = d.id_disponibilite
WHERE r.id_sportif = 1;  -- remplace 1 par l'id du sportif

--  Lister toutes les disponibilités d’un coach avec le statut des réservations
SELECT 
    d.id_disponibilite,
    d.date,
    d.heure_debut,
    d.heure_fin,
    d.etat,
    r.id_reservation,
    r.statut
FROM disponibilite d
LEFT JOIN reservation r ON d.id_disponibilite = r.id_disponibilite
WHERE d.id_coach = 1;


--  Lister tous les coachs avec nombre de réservations confirmées
SELECT 
    c.id_coach,
    c.nom,
    c.prenom,
    COUNT(r.id_reservation) AS nb_reservations
FROM coach c
LEFT JOIN reservation r ON c.id_coach = r.id_coach AND r.statut = 'confirmée'
GROUP BY c.id_coach;

-- Lister les séances d’un coach pour une date précise
SELECT 
    s.nom AS sportif_nom,
    s.prenom AS sportif_prenom,
    d.heure_debut,
    d.heure_fin,
    r.statut
FROM reservation r
JOIN sportif s ON r.id_sportif = s.id_sportif
JOIN disponibilite d ON r.id_disponibilite = d.id_disponibilite
WHERE r.id_coach = 1 AND d.date = '2025-12-18';