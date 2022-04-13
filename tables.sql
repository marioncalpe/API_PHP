START TRANSACTION;

CREATE TABLE `produits` (
    `id_produit` int(11) NOT NULL AUTO_INCREMENT COMMENT 'clé',
    `nom` varchar(80) NOT NULL,
    `date_in` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'date d''ajout',
    `date_up` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'date de maj',
    `description` text NOT NULL,
    `prix` float NOT NULL,
    CONSTRAINT pk_produits PRIMARY KEY(id_produit)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4;

CREATE TABLE `evolutions` (
    `id_evo` int(11) NOT NULL AUTO_INCREMENT COMMENT 'clé',
    `id_produit` int(11) NOT NULL COMMENT 'clé étrangère',
    `date_up` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'date de maj',
    `prix` float NOT NULL COMMENT 'prix maj',
    CONSTRAINT pk_evolutions PRIMARY KEY(id_evo),
    CONSTRAINT fk_evo_produit FOREIGN KEY(id_produit)
        REFERENCES produits(id_produit)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4;

INSERT INTO produits(nom, description, prix) VALUES 
    ('book', '', 10),
    ('pen', '', 15),
    ('pencil', '', 5);

COMMIT;