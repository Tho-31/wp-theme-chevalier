# thomas-theme
## release 1.0.1
contient tout le thomas-theme
## release 1.1.0
- ajout de la priorité dans les articles
    - ajout du plugin ACF
    - ajout du champ priority dans ACF

la priotirité permet d'ordonnencer les articles donc les oeuvre ou tableau lumineux
dans le slider ou la galerie. shortcode : slider, galerie.



SELECT ID, post_type , post_title, meta_value
FROM `wp_posts`
	LEFT JOIN wp_postmeta ON wp_postmeta.post_id = wp_posts.ID

INSERT INTO wp_postmeta (meta_key, meta_value, post_id)
SELECT 'priority', '', ID
FROM `wp_posts`
	LEFT JOIN wp_postmeta ON wp_postmeta.post_id = wp_posts.ID AND meta_key = 'priority'
WHERE
	post_type = 'post'
    AND meta_value IS NULL