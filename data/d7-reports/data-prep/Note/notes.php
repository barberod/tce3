$view = new view();
$view->name = 'export_notes';
$view->description = '';
$view->tag = 'tce3dataprep';
$view->base_table = 'node';
$view->human_name = 'Export_Notes';
$view->core = 7;
$view->api_version = '3.0';
$view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

/* Display: Master */
$handler = $view->new_display('default', 'Master', 'default');
$handler->display->display_options['title'] = 'Export_Notes';
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
/* Field: Content: Note Eval Ref */
$handler->display->display_options['fields']['field_note_eval_ref']['id'] = 'field_note_eval_ref';
$handler->display->display_options['fields']['field_note_eval_ref']['table'] = 'field_data_field_note_eval_ref';
$handler->display->display_options['fields']['field_note_eval_ref']['field'] = 'field_note_eval_ref';
$handler->display->display_options['fields']['field_note_eval_ref']['label'] = 'evaluation';
$handler->display->display_options['fields']['field_note_eval_ref']['type'] = 'node_reference_nid';
/* Field: Content: Note Body */
$handler->display->display_options['fields']['field_note_body']['id'] = 'field_note_body';
$handler->display->display_options['fields']['field_note_body']['table'] = 'field_data_field_note_body';
$handler->display->display_options['fields']['field_note_body']['field'] = 'field_note_body';
$handler->display->display_options['fields']['field_note_body']['label'] = 'body';
$handler->display->display_options['fields']['field_note_body']['type'] = 'text_trimmed';
$handler->display->display_options['fields']['field_note_body']['settings'] = array(
  'trim_length' => '600',
);
/* Field: Content: Note Visible to Student */
$handler->display->display_options['fields']['field_note_visible_to_student']['id'] = 'field_note_visible_to_student';
$handler->display->display_options['fields']['field_note_visible_to_student']['table'] = 'field_data_field_note_visible_to_student';
$handler->display->display_options['fields']['field_note_visible_to_student']['field'] = 'field_note_visible_to_student';
$handler->display->display_options['fields']['field_note_visible_to_student']['label'] = 'visible_to_requester';
/* Field: Content: Author uid */
$handler->display->display_options['fields']['uid']['id'] = 'uid';
$handler->display->display_options['fields']['uid']['table'] = 'node';
$handler->display->display_options['fields']['uid']['field'] = 'uid';
$handler->display->display_options['fields']['uid']['label'] = 'author';
$handler->display->display_options['fields']['uid']['link_to_user'] = FALSE;
/* Field: Content: Post date */
$handler->display->display_options['fields']['created']['id'] = 'created';
$handler->display->display_options['fields']['created']['table'] = 'node';
$handler->display->display_options['fields']['created']['field'] = 'created';
$handler->display->display_options['fields']['created']['label'] = 'created';
$handler->display->display_options['fields']['created']['date_format'] = 'standard';
$handler->display->display_options['fields']['created']['second_date_format'] = 'long';
/* Field: Content: Nid */
$handler->display->display_options['fields']['nid_1']['id'] = 'nid_1';
$handler->display->display_options['fields']['nid_1']['table'] = 'node';
$handler->display->display_options['fields']['nid_1']['field'] = 'nid';
$handler->display->display_options['fields']['nid_1']['label'] = 'd7_nid';
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
  'note' => 'note',
);
/* Filter criterion: Content: Nid */
$handler->display->display_options['filters']['nid']['id'] = 'nid';
$handler->display->display_options['filters']['nid']['table'] = 'node';
$handler->display->display_options['filters']['nid']['field'] = 'nid';
$handler->display->display_options['filters']['nid']['operator'] = '>=';
$handler->display->display_options['filters']['nid']['value']['value'] = '362874';

/* Display: Data export-01 */
$handler = $view->new_display('views_data_export', 'Data export-01', 'views_data_export_1');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '3000';
$handler->display->display_options['pager']['options']['offset'] = '0';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'notes-01.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-02 */
$handler = $view->new_display('views_data_export', 'Data export-02', 'views_data_export_2');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '3000';
$handler->display->display_options['pager']['options']['offset'] = '3000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'notes-02.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-03 */
$handler = $view->new_display('views_data_export', 'Data export-03', 'views_data_export_3');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '3000';
$handler->display->display_options['pager']['options']['offset'] = '6000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'notes-03.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-04 */
$handler = $view->new_display('views_data_export', 'Data export-04', 'views_data_export_4');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '3000';
$handler->display->display_options['pager']['options']['offset'] = '9000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'notes-04.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-05 */
$handler = $view->new_display('views_data_export', 'Data export-05', 'views_data_export_5');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '3000';
$handler->display->display_options['pager']['options']['offset'] = '12000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'notes-05.csv';
$handler->display->display_options['sitename_title'] = 0;
