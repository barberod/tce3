$view = new view();
$view->name = 'export_trails';
$view->description = '';
$view->tag = 'tce3dataprep';
$view->base_table = 'node';
$view->human_name = 'Export_Trails';
$view->core = 7;
$view->api_version = '3.0';
$view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

/* Display: Master */
$handler = $view->new_display('default', 'Master', 'default');
$handler->display->display_options['title'] = 'Export_Trails';
$handler->display->display_options['use_more_always'] = FALSE;
$handler->display->display_options['access']['type'] = 'perm';
$handler->display->display_options['cache']['type'] = 'none';
$handler->display->display_options['query']['type'] = 'views_query';
$handler->display->display_options['exposed_form']['type'] = 'basic';
$handler->display->display_options['pager']['type'] = 'full';
$handler->display->display_options['style_plugin'] = 'default';
$handler->display->display_options['row_plugin'] = 'fields';
/* Field: Content: Nid */
$handler->display->display_options['fields']['nid']['id'] = 'nid';
$handler->display->display_options['fields']['nid']['table'] = 'node';
$handler->display->display_options['fields']['nid']['field'] = 'nid';
$handler->display->display_options['fields']['nid']['label'] = 'nid';
/* Field: Content: Trail Eval Node Ref */
$handler->display->display_options['fields']['field_trail_eval_node_ref']['id'] = 'field_trail_eval_node_ref';
$handler->display->display_options['fields']['field_trail_eval_node_ref']['table'] = 'field_data_field_trail_eval_node_ref';
$handler->display->display_options['fields']['field_trail_eval_node_ref']['field'] = 'field_trail_eval_node_ref';
$handler->display->display_options['fields']['field_trail_eval_node_ref']['label'] = 'evaluation';
$handler->display->display_options['fields']['field_trail_eval_node_ref']['type'] = 'node_reference_nid';
/* Field: Content: Trail Body Full */
$handler->display->display_options['fields']['field_trail_body_full']['id'] = 'field_trail_body_full';
$handler->display->display_options['fields']['field_trail_body_full']['table'] = 'field_data_field_trail_body_full';
$handler->display->display_options['fields']['field_trail_body_full']['field'] = 'field_trail_body_full';
$handler->display->display_options['fields']['field_trail_body_full']['label'] = 'body';
$handler->display->display_options['fields']['field_trail_body_full']['type'] = 'text_trimmed';
$handler->display->display_options['fields']['field_trail_body_full']['settings'] = array(
  'trim_length' => '600',
);
/* Field: Content: Trail Body Limited */
$handler->display->display_options['fields']['field_trail_body_limited']['id'] = 'field_trail_body_limited';
$handler->display->display_options['fields']['field_trail_body_limited']['table'] = 'field_data_field_trail_body_limited';
$handler->display->display_options['fields']['field_trail_body_limited']['field'] = 'field_trail_body_limited';
$handler->display->display_options['fields']['field_trail_body_limited']['label'] = 'body_anon';
$handler->display->display_options['fields']['field_trail_body_limited']['type'] = 'text_trimmed';
$handler->display->display_options['fields']['field_trail_body_limited']['settings'] = array(
  'trim_length' => '600',
);
/* Field: Content: Post date */
$handler->display->display_options['fields']['created']['id'] = 'created';
$handler->display->display_options['fields']['created']['table'] = 'node';
$handler->display->display_options['fields']['created']['field'] = 'created';
$handler->display->display_options['fields']['created']['label'] = 'created';
$handler->display->display_options['fields']['created']['date_format'] = 'standard';
$handler->display->display_options['fields']['created']['second_date_format'] = 'long';
/* Sort criterion: Content: Post date */
$handler->display->display_options['sorts']['created']['id'] = 'created';
$handler->display->display_options['sorts']['created']['table'] = 'node';
$handler->display->display_options['sorts']['created']['field'] = 'created';
/* Filter criterion: Content: Published status */
$handler->display->display_options['filters']['status']['id'] = 'status';
$handler->display->display_options['filters']['status']['table'] = 'node';
$handler->display->display_options['filters']['status']['field'] = 'status';
$handler->display->display_options['filters']['status']['value'] = 1;
$handler->display->display_options['filters']['status']['group'] = 1;
$handler->display->display_options['filters']['status']['expose']['operator'] = FALSE;
/* Filter criterion: Content: Type */
$handler->display->display_options['filters']['type']['id'] = 'type';
$handler->display->display_options['filters']['type']['table'] = 'node';
$handler->display->display_options['filters']['type']['field'] = 'type';
$handler->display->display_options['filters']['type']['value'] = array(
  'trail' => 'trail',
);
/* Filter criterion: Content: Post date */
$handler->display->display_options['filters']['created']['id'] = 'created';
$handler->display->display_options['filters']['created']['table'] = 'node';
$handler->display->display_options['filters']['created']['field'] = 'created';
$handler->display->display_options['filters']['created']['operator'] = '>=';
$handler->display->display_options['filters']['created']['value']['value'] = '2022-05-12 15:15:00';

/* Display: Data export */
$handler = $view->new_display('views_data_export', 'Data export', 'views_data_export_1');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '3000';
$handler->display->display_options['pager']['options']['offset'] = '0';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'trails-01.csv';

/* Display: Data export-02 */
$handler = $view->new_display('views_data_export', 'Data export-02', 'views_data_export_2');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '3000';
$handler->display->display_options['pager']['options']['offset'] = '3000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'trails-02.csv';

/* Display: Data export-03 */
$handler = $view->new_display('views_data_export', 'Data export-03', 'views_data_export_3');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '3000';
$handler->display->display_options['pager']['options']['offset'] = '6000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'trails-03.csv';

/* Display: Data export-04 */
$handler = $view->new_display('views_data_export', 'Data export-04', 'views_data_export_4');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '3000';
$handler->display->display_options['pager']['options']['offset'] = '9000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'trails-04.csv';

/* Display: Data export-05 */
$handler = $view->new_display('views_data_export', 'Data export-05', 'views_data_export_5');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '3000';
$handler->display->display_options['pager']['options']['offset'] = '12000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'trails-05.csv';

/* Display: Data export-06 */
$handler = $view->new_display('views_data_export', 'Data export-06', 'views_data_export_6');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '3000';
$handler->display->display_options['pager']['options']['offset'] = '15000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'trails-06.csv';

/* Display: Data export-07 */
$handler = $view->new_display('views_data_export', 'Data export-07', 'views_data_export_7');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '3000';
$handler->display->display_options['pager']['options']['offset'] = '18000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'trails-07.csv';

/* Display: Data export-08 */
$handler = $view->new_display('views_data_export', 'Data export-08', 'views_data_export_8');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '3000';
$handler->display->display_options['pager']['options']['offset'] = '21000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'trails-08.csv';

/* Display: Data export-09 */
$handler = $view->new_display('views_data_export', 'Data export-09', 'views_data_export_9');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '3000';
$handler->display->display_options['pager']['options']['offset'] = '24000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'trails-09.csv';

/* Display: Data export-10 */
$handler = $view->new_display('views_data_export', 'Data export-10', 'views_data_export_10');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '3000';
$handler->display->display_options['pager']['options']['offset'] = '27000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'trails-10.csv';

/* Display: Data export-11 */
$handler = $view->new_display('views_data_export', 'Data export-11', 'views_data_export_11');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '3000';
$handler->display->display_options['pager']['options']['offset'] = '30000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'trails-11.csv';
