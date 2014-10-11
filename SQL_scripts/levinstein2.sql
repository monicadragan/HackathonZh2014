DELIMITER $$

CREATE FUNCTION `levenshtein_string`( string text, query text) RETURNS int(11)
    DETERMINISTIC
BEGIN 
 
DECLARE vDone tinyint(1) DEFAULT 1;
DECLARE oDone tinyint(1) DEFAULT 1;
DECLARE vIndex INT DEFAULT 1;
DECLARE oIndex INT DEFAULT 1;
DECLARE minDistance INT DEFAULT 10000;

DECLARE average FLOAT DEFAULT 0;
DECLARE cnt INT DEFAULT 0;

DECLARE leviDistance INT;
DECLARE vSubString VARCHAR(100);
DECLARE oSubString VARCHAR(100);
DECLARE delim VARCHAR(15) DEFAULT " ";

WHILE oDone > 0 DO
   SET vDone = 1;
   SET vIndex = 1;
   SET oSubString = SUBSTRING(string, oIndex,
		            IF(LOCATE(delim, string, oIndex) > 0,
		              LOCATE(delim, string, oIndex) - oIndex,
		              LENGTH(string)
		            ));

   IF LENGTH(oSubString) > 0 THEN
	SET oIndex = oIndex + LENGTH(oSubString) + 1;
 
	WHILE vDone > 0 DO
	  SET vSubString = SUBSTRING(query, vIndex,
		            IF(LOCATE(delim, query, vIndex) > 0,
		              LOCATE(delim, query, vIndex) - vIndex,
		              LENGTH(query)
		            ));

	  IF LENGTH(vSubString) > 0 THEN
	      SET vIndex = vIndex + LENGTH(vSubString) + 1;
	      SET leviDistance = levenshtein(oSubString, vSubString);
	#      IF(LENGTH(oSubString) > LENGTH(vSubString))
	#	  SET average = average + leviDistance / LENGTH(oSubString);
	#	  SET cnt  = cnt  + 1;
	#      ELSE
	#	  SET average = average + leviDistance / LENGTH(vSubString);
	#	  SET cnt  = cnt  + 1;
	#      END IF
	      IF leviDistance < minDistance THEN 
	      	SET minDistance = leviDistance;
	      END IF;
	      #INSERT INTO levi VALUES (oSubString, vSubString, leviDistance, minDistance, oIndex, vIndex);
	  ELSE
	      SET vDone = 0;
	  END IF;

	END WHILE;
    ELSE
	SET oDone = 0;
    END IF;

END WHILE;

#average = average / cnt;
RETURN minDistance;
 
END;

# test

# select levenshtein_string("Normal distribution", "distributions") as score;

