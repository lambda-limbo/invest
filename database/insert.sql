--
-- Insert default values for the admin roles.
--

USE INVEST;

-- The password values are examples because actually the password will be hashed.

CALL P_INSERT_ADM('Rafael Campos Nunes', 'ranu', '1nv35t@admin@ccoun1', '02086936290', 'ranu@invest.com', 
                  '999211031', '0000-00-00 00:00:00', 0.0);
CALL P_INSERT_ADM('Luiz Otavio Goebrey', 'ranu', '1nv35t@admin@ccoun2', '02654362950', 'luiz@invest.com', 
                  '996214862', '0000-00-00 00:00:00', 0.0);


CALL P_INSERT_STOCK(255.00, 244.00,233.00,222.00,1,23);