<?php
#header("content-type;text/html;charset=utf-8");

#database connection

class Data{

	const DB_USER = "root";
	const DB_PWD = "namo2010";
	
	const DB_NAME = "androidaid";
	
	const DB_TABLE_APP_REPOSITORY = "app_repository";
	
	const DB_COLUMN_APP_SERIAL = "app_serial";
	const DB_COLUMN_APP_CATEGORY = "app_category";
	const DB_COLUMN_APP_PACKAGE = "app_package";
	const DB_COLUMN_APP_LABEL = "app_label";
	const DB_COLUMN_APP_VERSION_CODE = "app_version_code";
	const DB_COLUMN_APP_VERSION_NAME = "app_version_name";
	const DB_COLUMN_APP_SDK_MIN = "app_sdk_min";
	const DB_COLUMN_APP_FILE = "app_file";
	const DB_COLUMN_APP_URL = "app_url";
	const DB_COLUMN_APP_ICON_FILE = "app_icon_file";
	const DB_COLUMN_APP_ICON_RESOURCE = "app_icon_reource";
	const DB_COLUMN_APP_OWNER = "app_owner";
	const DB_COLUMN_APP_VENDOR = "app_vendor";
	const DB_COLUMN_APP_AGENT = "app_agent";
	const DB_COLUMN_APP_CHANNEL = "app_channel";
	const DB_COLUMN_APP_STATE = "app_state";
	const DB_COLUMN_APP_PRICE = "app_price";
	const DB_COLUMN_APP_PRICING_POLICY = "app_pricing_policy";
	const DB_COLUMN_APP_CUSTOMER = "app_customer";
	const DB_COLUMN_APP_CUSTOMER_BRAND = "app_customer_brand";
	const DB_COLUMN_APP_CUSTOMER_MODEL = "app_customer_model";
	const DB_COLUMN_APP_FILE_ORIGINAL = "app_file_original";
	const DB_COLUMN_APP_DEPLOY_DATE = "app_deploy_date";
	const DB_COLUMN_APP_UPDATE_STAMP = "app_update_stamp";
	
/*	
define("DB_USER",     "root");
define("DB_PWD",     "namo2010");

define("DB_NAME",     "androidaid");

#database tables
define('DB_TABLE_APP_REPOSITORY', 'app_repository');
define('DB_TABLE_WIDGET_CONFIG', 'widget_config');
define('DB_TABLE_DEVICE_REGISTER', 'device_register');
define('DB_TABLE_DOWNLOAD_REPORTER', 'download_reporter');
define('DB_TABLE_INSTALL_REPORTER', 'install_reporter');
define('DB_TABLE_LAUNCH_REPORTER', 'launch_reporter');
define('DB_TABLE_LOCATION_REPORTER', 'location_reporter');
define('DB_TABLE_VERSION_REPOSITORY', 'version_repository');
define('DB_TABLE_CATEGORY_MANAGER', 'category_manager');


#database table app_repository
define('DB_COLUMN_APP_SERIAL', 'app_serial');
define('DB_COLUMN_APP_CATEGORY', 'app_category');
define('DB_COLUMN_APP_PACKAGE', 'app_package');
define('DB_COLUMN_APP_LABEL', 'app_label');
define('DB_COLUMN_APP_VERSION_CODE', 'app_version_code');
define('DB_COLUMN_APP_VERSION_NAME', 'app_version_name');
define('DB_COLUMN_APP_SDK_MIN', 'app_sdk_min');
define('DB_COLUMN_APP_FILE', 'app_file');
define('DB_COLUMN_APP_URL', 'app_url');
define('DB_COLUMN_APP_ICON_FILE', 'app_icon_file');
define('DB_COLUMN_APP_ICON_RESOURCE', 'app_icon_reource');
define('DB_COLUMN_APP_OWNER', 'app_owner');
define('DB_COLUMN_APP_VENDOR', 'app_vendor');
define('DB_COLUMN_APP_AGENT', 'app_agent');
define('DB_COLUMN_APP_CHANNEL', 'app_channel');
define('DB_COLUMN_APP_STATE', 'app_state');
define('DB_COLUMN_APP_PRICE', 'app_price');
define('DB_COLUMN_APP_PRICING_POLICY', 'app_pricing_policy');
define('DB_COLUMN_APP_CUSTOMER', 'app_customer');
define('DB_COLUMN_APP_CUSTOMER_BRAND', 'app_customer_brand');
define('DB_COLUMN_APP_CUSTOMER_MODEL', 'app_customer_model');
define('DB_COLUMN_APP_FILE_ORIGINAL', 'app_file_original');
define('DB_COLUMN_APP_DEPLOY_DATE', 'app_deploy_date');
define('DB_COLUMN_APP_UPDATE_STAMP', 'app_update_stamp');



$ArrayRepoColumns = array(
	DB_COLUMN_APP_SERIAL,
	DB_COLUMN_APP_CATEGORY,
	DB_COLUMN_APP_LABEL,
	DB_COLUMN_APP_STATE,
	DB_COLUMN_APP_PACKAGE,
	DB_COLUMN_APP_VERSION_CODE,
	DB_COLUMN_APP_FILE_ORIGINAL,
	DB_COLUMN_APP_VERSION_NAME,
	DB_COLUMN_APP_SDK_MIN,
	DB_COLUMN_APP_FILE,
#	DB_COLUMN_APP_URL,
#	DB_COLUMN_APP_ICON_FILE,
#	DB_COLUMN_APP_ICON_RESOURCE,
	DB_COLUMN_APP_OWNER,
	DB_COLUMN_APP_VENDOR,
	DB_COLUMN_APP_AGENT,
	DB_COLUMN_APP_CHANNEL,
	DB_COLUMN_APP_PRICE,
	DB_COLUMN_APP_PRICING_POLICY,
	DB_COLUMN_APP_CUSTOMER,
	DB_COLUMN_APP_CUSTOMER_BRAND,
	DB_COLUMN_APP_CUSTOMER_MODEL,
	DB_COLUMN_APP_DEPLOY_DATE,
	DB_COLUMN_APP_UPDATE_STAMP	
	);

$ArrayRepoCategoryUpdateColumns = array(
	DB_COLUMN_APP_SERIAL,
	DB_COLUMN_APP_CATEGORY,
	DB_COLUMN_APP_LABEL,
	DB_COLUMN_APP_FILE_ORIGINAL,
	);

$ArrayRepoDeployColumns = array(
	DB_COLUMN_APP_SERIAL,
	DB_COLUMN_APP_LABEL,
	DB_COLUMN_APP_STATE,
	DB_COLUMN_APP_FILE_ORIGINAL,
	DB_COLUMN_APP_OWNER,
	DB_COLUMN_APP_VENDOR,
	DB_COLUMN_APP_AGENT,
	DB_COLUMN_APP_CHANNEL,
	DB_COLUMN_APP_PRICE,
	DB_COLUMN_APP_PRICING_POLICY
	);


define('DB_VALUE_CATEGORY_IM', '����');
define('DB_VALUE_CATEGORY_GAME', '��Ϸ');
define('DB_VALUE_CATEGORY_MUSIC', '����');
define('DB_VALUE_CATEGORY_VIDEO', '��Ƶ');
define('DB_VALUE_CATEGORY_SHOPPING', '����');
define('DB_VALUE_CATEGORY_TRANSPORT', '��ͨ');
define('DB_VALUE_CATEGORY_WEATHER', '����');
define('DB_VALUE_CATEGORY_WEB', '����');
define('DB_VALUE_CATEGORY_READING', '�Ķ�');
define('DB_VALUE_CATEGORY_TOOLS', '����');
define('DB_VALUE_CATEGORY_PICTURE', '��ͼ');
define('DB_VALUE_CATEGORY_LIFE', '���');
define('DB_VALUE_CATEGORY_CAMERA', '����');
define('DB_VALUE_CATEGORY_NEWS', '����');

define('DB_VALUE_CATEGORY_ALL', 'all');

define('DB_VALUE_APP_STATE_DEPLOY', 'Online');
define('DB_VALUE_APP_STATE_EXPIRED', 'Offline');
define('DB_VALUE_APP_STATE_UPLOAD', 'Upload');


#database table category_manager
define('DB_COLUMN_CATEGORY_SERIAL', 'category_serial');
define('DB_COLUMN_CATEGORY_DIR', 'category_dir');
define('DB_COLUMN_CATEGORY_STATUS', 'category_status');
define('DB_COLUMN_CATEGORY_MAPPING', 'category_mapping');
define('DB_COLUMN_CATEGORY_STAMP', 'category_stamp');

define('DB_VALUE_CATEGORY_STATUS_ONLINE', 'Online');

$ArrayCategoryColumns = array(
	DB_COLUMN_CATEGORY_SERIAL,
	DB_COLUMN_APP_CATEGORY,
	DB_COLUMN_CATEGORY_DIR,
	DB_COLUMN_CATEGORY_STATUS,
	DB_COLUMN_CATEGORY_MAPPING,
	DB_COLUMN_CATEGORY_STAMP
	);


#database table device_register
define('DB_COLUMN_DEVICE_SERIAL', 'device_serial');
define('DB_COLUMN_DEVICE_IMEI', 'device_imei');
define('DB_COLUMN_DEVICE_BRAND', 'device_brand');
define('DB_COLUMN_DEVICE_MODEL', 'device_model');
define('DB_COLUMN_DEVICE_SDK', 'device_sdk');
define('DB_COLUMN_VERSION_CODE', 'version_code');
define('DB_COLUMN_DEVICE_REGIST_STAMP', 'device_regist_stamp');

$ArrayDeviceColumns = array(
	DB_COLUMN_DEVICE_SERIAL,
	DB_COLUMN_DEVICE_IMEI,
	DB_COLUMN_DEVICE_BRAND,
	DB_COLUMN_DEVICE_MODEL,
	DB_COLUMN_DEVICE_SDK,
	DB_COLUMN_VERSION_CODE,
	DB_COLUMN_DEVICE_REGIST_STAMP
	);

#database table download_reporter
define('DB_COLUMN_DOWNLOAD_SERIAL', 'download_serial');
define('DB_COLUMN_DOWNLOAD_START', 'app_download_start');
define('DB_COLUMN_DOWNLOAD_STOP', 'app_download_stop');
define('DB_COLUMN_DOWNLOAD_REPORTER_STAMP', 'download_reporter_stamp');


$ArrayDownloadColumns = array(
	DB_COLUMN_DOWNLOAD_SERIAL,
	DB_COLUMN_DEVICE_IMEI,
	DB_COLUMN_VERSION_CODE,
	DB_COLUMN_APP_PACKAGE,
	DB_COLUMN_APP_LABEL,
	DB_COLUMN_APP_VERSION_CODE,
	DB_COLUMN_APP_URL,
	DB_COLUMN_DOWNLOAD_START,
	DB_COLUMN_DOWNLOAD_STOP,
	DB_COLUMN_DOWNLOAD_REPORTER_STAMP
	);

#database table install_reporter
define('DB_COLUMN_INSTALL_SERIAL', 'install_serial');
#define('DB_COLUMN_FIRST_INSTALL_TIME', 'first_install_time');
#define('DB_COLUMN_LAST_UPDATE_TIME', 'last_update_time');
define('DB_COLUMN_INSTALL_ACTION', 'install_action');
define('DB_COLUMN_INSTALL_ACTION_TIME', 'install_action_time');
define('DB_COLUMN_INSTALL_REPORTER_STAMP', 'install_reporter_stamp');


$ArrayInstallColumns = array(
	DB_COLUMN_INSTALL_SERIAL,
	DB_COLUMN_DEVICE_IMEI,
	DB_COLUMN_VERSION_CODE,
	DB_COLUMN_APP_PACKAGE,
	DB_COLUMN_APP_LABEL,
	DB_COLUMN_APP_VERSION_CODE,
#	DB_COLUMN_FIRST_INSTALL_TIME,
#	DB_COLUMN_LAST_UPDATE_TIME,
	DB_COLUMN_INSTALL_ACTION,
	DB_COLUMN_INSTALL_ACTION_TIME,
	DB_COLUMN_INSTALL_REPORTER_STAMP
	);

#database table launch_reporter
define('DB_COLUMN_LAUNCH_SERIAL', 'launch_serial');
define('DB_COLUMN_LAUNCH_TIME', 'launch_time');
define('DB_COLUMN_LAUNCH_BY_ME', 'launch_by_me');
define('DB_COLUMN_LAUNCH_REPORT_STAMP', 'launch_report_stamp');


$ArrayLaunchColumns = array(
	DB_COLUMN_LAUNCH_SERIAL,
	DB_COLUMN_DEVICE_IMEI,
	DB_COLUMN_APP_PACKAGE,
	DB_COLUMN_APP_LABEL,
	DB_COLUMN_APP_VERSION_CODE,
	DB_COLUMN_LAUNCH_TIME,
	DB_COLUMN_LAUNCH_BY_ME,
	DB_COLUMN_LAUNCH_REPORT_STAMP
	);


#database table location_reporter
define('DB_COLUMN_LOCATION_SERIAL', 'location_serial');
define('DB_COLUMN_LOCATION_TIME', 'location_time');
define('DB_COLUMN_LOCATION_LATE6', 'location_late6');
define('DB_COLUMN_LOCATION_LONGE6', 'location_longe6');
define('DB_COLUMN_LOCATION_IP', 'location_ip');
define('DB_COLUMN_LOCATION_CITY', 'location_city');
define('DB_COLUMN_LOCATION_REPORT_STAMP', 'location_report_stamp');


$ArrayLocationColumns = array(
	DB_COLUMN_LOCATION_SERIAL,
	DB_COLUMN_DEVICE_IMEI,
	DB_COLUMN_LOCATION_TIME,
	DB_COLUMN_LOCATION_LATE6,
	DB_COLUMN_LOCATION_LONGE6,
	DB_COLUMN_LOCATION_IP,
	DB_COLUMN_LOCATION_CITY,
	DB_COLUMN_LOCATION_REPORT_STAMP
	);
	
#database table version_repository
define('DB_COLUMN_VERSION_SERIAL', 'version_serial');
#define('DB_COLUMN_VERSION_CODE', 'version_code');
define('DB_COLUMN_VERSION_NAME', 'version_name');
define('DB_COLUMN_VERSION_SDK', 'version_sdk');
define('DB_COLUMN_VERSION_CUSTOMER', 'version_customer');
define('DB_COLUMN_VERSION_BRAND', 'version_brand');
define('DB_COLUMN_VERSION_MODEL', 'version_model');
define('DB_COLUMN_VERSION_FILE', 'version_file');
define('DB_COLUMN_VERSION_NEW', 'version_new');
define('DB_COLUMN_VERSION_STAMP', 'version_stamp');


$ArrayVersionColumns = array(
	DB_COLUMN_VERSION_SERIAL,
	DB_COLUMN_VERSION_CODE,
	DB_COLUMN_VERSION_NAME,
	DB_COLUMN_VERSION_FILE,
	DB_COLUMN_VERSION_NEW,
	DB_COLUMN_VERSION_SDK,
	DB_COLUMN_VERSION_CUSTOMER,
	DB_COLUMN_VERSION_BRAND,
	DB_COLUMN_VERSION_MODEL,
	DB_COLUMN_VERSION_STAMP
	);	
	
#api vrsion	
define('API_VERSION', 'api_version');

define('API_VERSION_VALUE_1', '1.0');


#web api parameters
define('API_ENTRY', 'api_entry');
define('API_JSON_RESPONSE', 'api_response');
define('API_JSON_RESPONSE_TRUE', true);
define('API_JSON_RESPONSE_FALSE', false);

define('API_JSON_RESPONSE_NUM', 'api_response_num');

define('API_JSON_APP_ARRAY', 'app_array');
define('API_JSON_CATEGORY_ARRAY', 'category_array');
*/
	
}

?>
