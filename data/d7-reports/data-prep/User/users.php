$view = new view();
$view->name = 'export_users';
$view->description = '';
$view->tag = 'tce3dataprep';
$view->base_table = 'users';
$view->human_name = 'Export_Users';
$view->core = 7;
$view->api_version = '3.0';
$view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

/* Display: Master */
$handler = $view->new_display('default', 'Master', 'default');
$handler->display->display_options['title'] = 'Export_Users';
$handler->display->display_options['use_more_always'] = FALSE;
$handler->display->display_options['access']['type'] = 'perm';
$handler->display->display_options['access']['perm'] = 'access user profiles';
$handler->display->display_options['cache']['type'] = 'none';
$handler->display->display_options['query']['type'] = 'views_query';
$handler->display->display_options['exposed_form']['type'] = 'basic';
$handler->display->display_options['pager']['type'] = 'full';
$handler->display->display_options['style_plugin'] = 'default';
$handler->display->display_options['row_plugin'] = 'fields';
/* Field: User: Name */
$handler->display->display_options['fields']['name']['id'] = 'name';
$handler->display->display_options['fields']['name']['table'] = 'users';
$handler->display->display_options['fields']['name']['field'] = 'name';
$handler->display->display_options['fields']['name']['label'] = 'username';
$handler->display->display_options['fields']['name']['alter']['word_boundary'] = FALSE;
$handler->display->display_options['fields']['name']['alter']['ellipsis'] = FALSE;
$handler->display->display_options['fields']['name']['element_label_colon'] = FALSE;
$handler->display->display_options['fields']['name']['link_to_user'] = FALSE;
/* Field: User: User GTID */
$handler->display->display_options['fields']['field_user_gtid']['id'] = 'field_user_gtid';
$handler->display->display_options['fields']['field_user_gtid']['table'] = 'field_data_field_user_gtid';
$handler->display->display_options['fields']['field_user_gtid']['field'] = 'field_user_gtid';
$handler->display->display_options['fields']['field_user_gtid']['label'] = 'orgID';
$handler->display->display_options['fields']['field_user_gtid']['element_label_colon'] = FALSE;
/* Field: User: User Display Name */
$handler->display->display_options['fields']['field_user_display_name']['id'] = 'field_user_display_name';
$handler->display->display_options['fields']['field_user_display_name']['table'] = 'field_data_field_user_display_name';
$handler->display->display_options['fields']['field_user_display_name']['field'] = 'field_user_display_name';
$handler->display->display_options['fields']['field_user_display_name']['label'] = 'displayName';
$handler->display->display_options['fields']['field_user_display_name']['element_label_colon'] = FALSE;
/* Field: User: User GT Email */
$handler->display->display_options['fields']['field_user_gt_email']['id'] = 'field_user_gt_email';
$handler->display->display_options['fields']['field_user_gt_email']['table'] = 'field_data_field_user_gt_email';
$handler->display->display_options['fields']['field_user_gt_email']['field'] = 'field_user_gt_email';
$handler->display->display_options['fields']['field_user_gt_email']['label'] = 'email';
/* Field: User: Uid */
$handler->display->display_options['fields']['uid']['id'] = 'uid';
$handler->display->display_options['fields']['uid']['table'] = 'users';
$handler->display->display_options['fields']['uid']['field'] = 'uid';
$handler->display->display_options['fields']['uid']['label'] = 'category';
$handler->display->display_options['fields']['uid']['alter']['alter_text'] = TRUE;
$handler->display->display_options['fields']['uid']['alter']['text'] = 'unknown';
$handler->display->display_options['fields']['uid']['link_to_user'] = FALSE;
/* Field: User: Active status */
$handler->display->display_options['fields']['status']['id'] = 'status';
$handler->display->display_options['fields']['status']['table'] = 'users';
$handler->display->display_options['fields']['status']['field'] = 'status';
$handler->display->display_options['fields']['status']['label'] = 'status';
$handler->display->display_options['fields']['status']['element_label_colon'] = FALSE;
$handler->display->display_options['fields']['status']['type'] = 'boolean';
$handler->display->display_options['fields']['status']['not'] = 0;
/* Field: User: Uid */
$handler->display->display_options['fields']['uid_1']['id'] = 'uid_1';
$handler->display->display_options['fields']['uid_1']['table'] = 'users';
$handler->display->display_options['fields']['uid_1']['field'] = 'uid';
$handler->display->display_options['fields']['uid_1']['label'] = 'frozen';
$handler->display->display_options['fields']['uid_1']['alter']['alter_text'] = TRUE;
$handler->display->display_options['fields']['uid_1']['alter']['text'] = '0';
$handler->display->display_options['fields']['uid_1']['element_label_colon'] = FALSE;
$handler->display->display_options['fields']['uid_1']['link_to_user'] = FALSE;
/* Field: User: Roles */
$handler->display->display_options['fields']['rid']['id'] = 'rid';
$handler->display->display_options['fields']['rid']['table'] = 'users_roles';
$handler->display->display_options['fields']['rid']['field'] = 'rid';
$handler->display->display_options['fields']['rid']['display_roles'] = array(
  8 => 0,
  7 => 0,
  6 => 0,
  5 => 0,
  4 => 0,
  12 => 0,
  9 => 0,
  10 => 0,
  11 => 0,
  3 => 0,
);
/* Field: User: Uid */
$handler->display->display_options['fields']['uid_2']['id'] = 'uid_2';
$handler->display->display_options['fields']['uid_2']['table'] = 'users';
$handler->display->display_options['fields']['uid_2']['field'] = 'uid';
$handler->display->display_options['fields']['uid_2']['label'] = 'd7uid';
$handler->display->display_options['fields']['uid_2']['link_to_user'] = FALSE;
/* Sort criterion: User: Uid */
$handler->display->display_options['sorts']['uid']['id'] = 'uid';
$handler->display->display_options['sorts']['uid']['table'] = 'users';
$handler->display->display_options['sorts']['uid']['field'] = 'uid';
/* Filter criterion: User: Active status */
$handler->display->display_options['filters']['status']['id'] = 'status';
$handler->display->display_options['filters']['status']['table'] = 'users';
$handler->display->display_options['filters']['status']['field'] = 'status';
$handler->display->display_options['filters']['status']['value'] = '1';
$handler->display->display_options['filters']['status']['group'] = 1;
$handler->display->display_options['filters']['status']['expose']['operator'] = FALSE;

/* Display: Data export-01 */
$handler = $view->new_display('views_data_export', 'Data export-01', 'views_data_export_1');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '1000';
$handler->display->display_options['pager']['options']['offset'] = '0';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'users-01.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-02 */
$handler = $view->new_display('views_data_export', 'Data export-02', 'views_data_export_2');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '1000';
$handler->display->display_options['pager']['options']['offset'] = '1000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'users-02.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-03 */
$handler = $view->new_display('views_data_export', 'Data export-03', 'views_data_export_3');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '1000';
$handler->display->display_options['pager']['options']['offset'] = '2000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'users-03.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-04 */
$handler = $view->new_display('views_data_export', 'Data export-04', 'views_data_export_4');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '1000';
$handler->display->display_options['pager']['options']['offset'] = '3000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'users-04.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-05 */
$handler = $view->new_display('views_data_export', 'Data export-05', 'views_data_export_5');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '1000';
$handler->display->display_options['pager']['options']['offset'] = '4000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'users-05.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-06 */
$handler = $view->new_display('views_data_export', 'Data export-06', 'views_data_export_6');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '1000';
$handler->display->display_options['pager']['options']['offset'] = '5000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'users-06.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-07 */
$handler = $view->new_display('views_data_export', 'Data export-07', 'views_data_export_7');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '1000';
$handler->display->display_options['pager']['options']['offset'] = '6000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'users-07.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-08 */
$handler = $view->new_display('views_data_export', 'Data export-08', 'views_data_export_8');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '1000';
$handler->display->display_options['pager']['options']['offset'] = '7000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'users-08.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-09 */
$handler = $view->new_display('views_data_export', 'Data export-09', 'views_data_export_9');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '1000';
$handler->display->display_options['pager']['options']['offset'] = '8000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'users-09.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-10 */
$handler = $view->new_display('views_data_export', 'Data export-10', 'views_data_export_10');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '1000';
$handler->display->display_options['pager']['options']['offset'] = '9000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'users-10.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-11 */
$handler = $view->new_display('views_data_export', 'Data export-11', 'views_data_export_11');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '1000';
$handler->display->display_options['pager']['options']['offset'] = '10000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'users-11.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-12 */
$handler = $view->new_display('views_data_export', 'Data export-12', 'views_data_export_12');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '1000';
$handler->display->display_options['pager']['options']['offset'] = '11000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'users-12.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-13 */
$handler = $view->new_display('views_data_export', 'Data export-13', 'views_data_export_13');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '1000';
$handler->display->display_options['pager']['options']['offset'] = '12000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'users-13.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-14 */
$handler = $view->new_display('views_data_export', 'Data export-14', 'views_data_export_14');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '1000';
$handler->display->display_options['pager']['options']['offset'] = '13000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'users-14.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-15 */
$handler = $view->new_display('views_data_export', 'Data export-15', 'views_data_export_15');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '1000';
$handler->display->display_options['pager']['options']['offset'] = '14000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'users-15.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-16 */
$handler = $view->new_display('views_data_export', 'Data export-16', 'views_data_export_16');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '1000';
$handler->display->display_options['pager']['options']['offset'] = '15000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'users-16.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-17 */
$handler = $view->new_display('views_data_export', 'Data export-17', 'views_data_export_17');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '1000';
$handler->display->display_options['pager']['options']['offset'] = '16000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'users-17.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-18 */
$handler = $view->new_display('views_data_export', 'Data export-18', 'views_data_export_18');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '1000';
$handler->display->display_options['pager']['options']['offset'] = '17000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'users-18.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-19 */
$handler = $view->new_display('views_data_export', 'Data export-19', 'views_data_export_19');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '1000';
$handler->display->display_options['pager']['options']['offset'] = '18000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'users-19.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-20 */
$handler = $view->new_display('views_data_export', 'Data export-20', 'views_data_export_20');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '1000';
$handler->display->display_options['pager']['options']['offset'] = '19000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'users-20.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-21 */
$handler = $view->new_display('views_data_export', 'Data export-21', 'views_data_export_21');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '1000';
$handler->display->display_options['pager']['options']['offset'] = '20000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'users-21.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-22 */
$handler = $view->new_display('views_data_export', 'Data export-22', 'views_data_export_22');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '1000';
$handler->display->display_options['pager']['options']['offset'] = '21000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'users-22.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-23 */
$handler = $view->new_display('views_data_export', 'Data export-23', 'views_data_export_23');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '1000';
$handler->display->display_options['pager']['options']['offset'] = '22000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'users-23.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-24 */
$handler = $view->new_display('views_data_export', 'Data export-24', 'views_data_export_24');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '1000';
$handler->display->display_options['pager']['options']['offset'] = '23000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'users-24.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-25 */
$handler = $view->new_display('views_data_export', 'Data export-25', 'views_data_export_25');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '1000';
$handler->display->display_options['pager']['options']['offset'] = '24000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'users-25.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-26 */
$handler = $view->new_display('views_data_export', 'Data export-26', 'views_data_export_26');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '1000';
$handler->display->display_options['pager']['options']['offset'] = '25000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'users-26.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-27 */
$handler = $view->new_display('views_data_export', 'Data export-27', 'views_data_export_27');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '1000';
$handler->display->display_options['pager']['options']['offset'] = '26000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'users-27.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-28 */
$handler = $view->new_display('views_data_export', 'Data export-28', 'views_data_export_28');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '1000';
$handler->display->display_options['pager']['options']['offset'] = '27000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'users-28.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-29 */
$handler = $view->new_display('views_data_export', 'Data export-29', 'views_data_export_29');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '1000';
$handler->display->display_options['pager']['options']['offset'] = '28000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'users-29.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-30 */
$handler = $view->new_display('views_data_export', 'Data export-30', 'views_data_export_30');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '1000';
$handler->display->display_options['pager']['options']['offset'] = '29000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'users-30.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-31 */
$handler = $view->new_display('views_data_export', 'Data export-31', 'views_data_export_31');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '1000';
$handler->display->display_options['pager']['options']['offset'] = '30000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'users-31.csv';
$handler->display->display_options['sitename_title'] = 0;
