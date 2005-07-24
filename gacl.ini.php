;
; This file has the .php extension at the end 
; as a security measure incase you don't remove
; it from your webroot directory. This should 
; keep prying eyes away.
;

debug 			= FALSE

;
;Database
;
db_type 		= "mysql"
db_host			= "localhost"
db_user			= "root"
db_password		= ""
db_name			= "gacl"
db_table_prefix		= ""

;
;Caching
;
caching			= FALSE
force_cache_expire	= TRUE
cache_dir		= "/tmp/phpgacl_cache"
cache_expire_time	= 600

;
;Admin interface
;
items_per_page 		= 100
max_select_box_items 	= 100
max_search_return_items = 200
