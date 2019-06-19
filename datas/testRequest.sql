# on sélectionne tous les étudiants de la section actuelle grâce à l'id de la section
SELECT thestudent.*
	FROM thestudent
    INNER JOIN thesection_has_thestudent
		ON thesection_has_thestudent.thestudent_idthestudent= thestudent.idthestudent
    WHERE  thesection_has_thestudent.thesection_idthesection=1;
    
# on vérifie la validité d'une connexion login/password
SELECT idtheuser, thelogin FROM theuser
		WHERE thelogin = 'lulu' AND thepwd = 'd2435e88f3575be3ee762a3183629548165f9ed6a81a6ab13725967e3c72ef36';

# Sélection de toutes les sections (avec 100 caractères de thedesc) et les (noms et prénoms) des thestudent liés, pour la page d'accueil de l'administration du site, les sections sans utilisateurs doivent apparaître également !!!        
SELECT a.idthesection, a.thetitle, LEFT(a.thedesc,100) AS thedesc,
	GROUP_CONCAT(c.thename SEPARATOR '|||') AS thename, 
    GROUP_CONCAT(c.thesurname SEPARATOR '|||') AS thesurname
	FROM thesection a
		LEFT JOIN thesection_has_thestudent b
			ON a.idthesection = b.thesection_idthesection
		LEFT JOIN thestudent c
			ON b.thestudent_idthestudent = c.idthestudent
    GROUP BY a.idthesection        
    ;      



  