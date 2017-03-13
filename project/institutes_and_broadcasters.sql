CREATE VIEW `institutes_and_broadcasters` AS
    SELECT 
        Institute.id AS institute_id,
        Institute.name AS institute_name,
        Broadcaster.id AS broadcaster_id,
        Broadcaster.name AS broadcaster_name,
        Institute_has_Broadcaster.userName,
        Institute_has_Broadcaster.link
    FROM
        Institute_has_Broadcaster
            JOIN
        Broadcaster ON Institute_has_Broadcaster.broadcaster_id = Broadcaster.id
            JOIN
        Institute ON Institute_has_Broadcaster.institute_id = Institute.id