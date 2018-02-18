**Test Instructions**

https://github.com/SamKnows/backend-test

**Solution**

_Micro-framework_ - Silex 2.0;
_DB_ - Mysql and SqlLite for Tests;
     - The schema is available in config/DB_schema.sql
_PHP_ - 7.1

The Rest Api has 2 endpoints(which can be seen running: php src/list_routes.php
):
 ========= ================================== 
  methods   path                              
 ========= ================================== 
  GET       /measures/{unit_id}/{day}/{hour}  
  POST      /measures                         
 ========= ================================== 

The GET endpoint will return an json with min, max, median, mean and count for each type of metrics
The POST will insert in the 4 tables (download, upload, latency, packet_loss) 

**Comments**
The solution provided is just a proof of concept, it can definitely be improved.
What I would do more if time would allow it: 
 - Use Data transfer objects for validation of the data and for the response
 - User Table Mapping into entities and get rid of the native queries
 - Handle Customised Exception 
 - API Documentation
 - More tests