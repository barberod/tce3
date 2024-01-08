$view = new view();
$view->name = 'export_evaluations';
$view->description = '';
$view->tag = 'tce3dataprep';
$view->base_table = 'node';
$view->human_name = 'Export_Evaluations';
$view->core = 7;
$view->api_version = '3.0';
$view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

/* Display: Master */
$handler = $view->new_display('default', 'Master', 'default');
$handler->display->display_options['title'] = 'Export_Evaluations';
$handler->display->display_options['use_more_always'] = FALSE;
$handler->display->display_options['access']['type'] = 'perm';
$handler->display->display_options['cache']['type'] = 'none';
$handler->display->display_options['query']['type'] = 'views_query';
$handler->display->display_options['exposed_form']['type'] = 'basic';
$handler->display->display_options['pager']['type'] = 'full';
$handler->display->display_options['style_plugin'] = 'default';
$handler->display->display_options['row_plugin'] = 'fields';
/* Relationship: Content: Eval University (field_eval_university) */
$handler->display->display_options['relationships']['field_eval_university_nid']['id'] = 'field_eval_university_nid';
$handler->display->display_options['relationships']['field_eval_university_nid']['table'] = 'field_data_field_eval_university';
$handler->display->display_options['relationships']['field_eval_university_nid']['field'] = 'field_eval_university_nid';
$handler->display->display_options['relationships']['field_eval_university_nid']['delta'] = '-1';
/* Field: Content: Nid */
$handler->display->display_options['fields']['nid']['id'] = 'nid';
$handler->display->display_options['fields']['nid']['table'] = 'node';
$handler->display->display_options['fields']['nid']['field'] = 'nid';
/* Field: Content: Eval Serial Number */
$handler->display->display_options['fields']['field_eval_serial_number']['id'] = 'field_eval_serial_number';
$handler->display->display_options['fields']['field_eval_serial_number']['table'] = 'field_data_field_eval_serial_number';
$handler->display->display_options['fields']['field_eval_serial_number']['field'] = 'field_eval_serial_number';
$handler->display->display_options['fields']['field_eval_serial_number']['label'] = 'serial_num';
$handler->display->display_options['fields']['field_eval_serial_number']['empty'] = '404';
$handler->display->display_options['fields']['field_eval_serial_number']['empty_zero'] = TRUE;
$handler->display->display_options['fields']['field_eval_serial_number']['hide_alter_empty'] = FALSE;
/* Field: Content: Author uid */
$handler->display->display_options['fields']['uid']['id'] = 'uid';
$handler->display->display_options['fields']['uid']['table'] = 'node';
$handler->display->display_options['fields']['uid']['field'] = 'uid';
$handler->display->display_options['fields']['uid']['label'] = 'requester';
/* Field: Content: Eval Required Admission */
$handler->display->display_options['fields']['field_eval_required_admission']['id'] = 'field_eval_required_admission';
$handler->display->display_options['fields']['field_eval_required_admission']['table'] = 'field_data_field_eval_required_admission';
$handler->display->display_options['fields']['field_eval_required_admission']['field'] = 'field_eval_required_admission';
$handler->display->display_options['fields']['field_eval_required_admission']['label'] = 'req_admin';
/* Field: Content: Nid */
$handler->display->display_options['fields']['nid_1']['id'] = 'nid_1';
$handler->display->display_options['fields']['nid_1']['table'] = 'node';
$handler->display->display_options['fields']['nid_1']['field'] = 'nid';
$handler->display->display_options['fields']['nid_1']['relationship'] = 'field_eval_university_nid';
$handler->display->display_options['fields']['nid_1']['label'] = 'institution_nid';
/* Field: Content: Eval Univ Other Univ */
$handler->display->display_options['fields']['field_eval_univ_other_univ']['id'] = 'field_eval_univ_other_univ';
$handler->display->display_options['fields']['field_eval_univ_other_univ']['table'] = 'field_data_field_eval_univ_other_univ';
$handler->display->display_options['fields']['field_eval_univ_other_univ']['field'] = 'field_eval_univ_other_univ';
$handler->display->display_options['fields']['field_eval_univ_other_univ']['label'] = 'institution_other';
/* Field: Content: Eval Univ Other Country */
$handler->display->display_options['fields']['field_eval_univ_other_country']['id'] = 'field_eval_univ_other_country';
$handler->display->display_options['fields']['field_eval_univ_other_country']['table'] = 'field_data_field_eval_univ_other_country';
$handler->display->display_options['fields']['field_eval_univ_other_country']['field'] = 'field_eval_univ_other_country';
$handler->display->display_options['fields']['field_eval_univ_other_country']['label'] = 'institution_country';
/* Field: Content: Eval Subject Code */
$handler->display->display_options['fields']['field_eval_subject_code']['id'] = 'field_eval_subject_code';
$handler->display->display_options['fields']['field_eval_subject_code']['table'] = 'field_data_field_eval_subject_code';
$handler->display->display_options['fields']['field_eval_subject_code']['field'] = 'field_eval_subject_code';
$handler->display->display_options['fields']['field_eval_subject_code']['label'] = 'course_subj_code';
/* Field: Content: Eval Course Number */
$handler->display->display_options['fields']['field_eval_course_number']['id'] = 'field_eval_course_number';
$handler->display->display_options['fields']['field_eval_course_number']['table'] = 'field_data_field_eval_course_number';
$handler->display->display_options['fields']['field_eval_course_number']['field'] = 'field_eval_course_number';
$handler->display->display_options['fields']['field_eval_course_number']['label'] = 'course_crse_num';
/* Field: Content: Eval Academic Term */
$handler->display->display_options['fields']['field_eval_academic_term']['id'] = 'field_eval_academic_term';
$handler->display->display_options['fields']['field_eval_academic_term']['table'] = 'field_data_field_eval_academic_term';
$handler->display->display_options['fields']['field_eval_academic_term']['field'] = 'field_eval_academic_term';
$handler->display->display_options['fields']['field_eval_academic_term']['label'] = 'course_term';
/* Field: Content: Eval Credit Hrs */
$handler->display->display_options['fields']['field_eval_credit_hrs']['id'] = 'field_eval_credit_hrs';
$handler->display->display_options['fields']['field_eval_credit_hrs']['table'] = 'field_data_field_eval_credit_hrs';
$handler->display->display_options['fields']['field_eval_credit_hrs']['field'] = 'field_eval_credit_hrs';
$handler->display->display_options['fields']['field_eval_credit_hrs']['label'] = 'course_credit_hrs';
/* Field: Content: Eval Academic Term Basis */
$handler->display->display_options['fields']['field_eval_academic_term_basis']['id'] = 'field_eval_academic_term_basis';
$handler->display->display_options['fields']['field_eval_academic_term_basis']['table'] = 'field_data_field_eval_academic_term_basis';
$handler->display->display_options['fields']['field_eval_academic_term_basis']['field'] = 'field_eval_academic_term_basis';
$handler->display->display_options['fields']['field_eval_academic_term_basis']['label'] = 'course_credit_basis';
/* Field: Content: Eval Lab Subject Code */
$handler->display->display_options['fields']['field_eval_lab_subject_code']['id'] = 'field_eval_lab_subject_code';
$handler->display->display_options['fields']['field_eval_lab_subject_code']['table'] = 'field_data_field_eval_lab_subject_code';
$handler->display->display_options['fields']['field_eval_lab_subject_code']['field'] = 'field_eval_lab_subject_code';
$handler->display->display_options['fields']['field_eval_lab_subject_code']['label'] = 'lab_subj_code';
/* Field: Content: Eval Lab Course Number */
$handler->display->display_options['fields']['field_eval_lab_course_number']['id'] = 'field_eval_lab_course_number';
$handler->display->display_options['fields']['field_eval_lab_course_number']['table'] = 'field_data_field_eval_lab_course_number';
$handler->display->display_options['fields']['field_eval_lab_course_number']['field'] = 'field_eval_lab_course_number';
$handler->display->display_options['fields']['field_eval_lab_course_number']['label'] = 'lab_crse_num';
/* Field: Content: Eval Lab Academic Term */
$handler->display->display_options['fields']['field_eval_lab_academic_term']['id'] = 'field_eval_lab_academic_term';
$handler->display->display_options['fields']['field_eval_lab_academic_term']['table'] = 'field_data_field_eval_lab_academic_term';
$handler->display->display_options['fields']['field_eval_lab_academic_term']['field'] = 'field_eval_lab_academic_term';
$handler->display->display_options['fields']['field_eval_lab_academic_term']['label'] = 'lab_term';
/* Field: Content: Eval Lab Credit Hrs */
$handler->display->display_options['fields']['field_eval_lab_credit_hrs']['id'] = 'field_eval_lab_credit_hrs';
$handler->display->display_options['fields']['field_eval_lab_credit_hrs']['table'] = 'field_data_field_eval_lab_credit_hrs';
$handler->display->display_options['fields']['field_eval_lab_credit_hrs']['field'] = 'field_eval_lab_credit_hrs';
$handler->display->display_options['fields']['field_eval_lab_credit_hrs']['label'] = 'lab_credit_hrs';
/* Field: Content: Eval Lab Acad Term Basis */
$handler->display->display_options['fields']['field_eval_lab_acad_term_basis']['id'] = 'field_eval_lab_acad_term_basis';
$handler->display->display_options['fields']['field_eval_lab_acad_term_basis']['table'] = 'field_data_field_eval_lab_acad_term_basis';
$handler->display->display_options['fields']['field_eval_lab_acad_term_basis']['field'] = 'field_eval_lab_acad_term_basis';
$handler->display->display_options['fields']['field_eval_lab_acad_term_basis']['label'] = 'lab_credit_basis';
/* Field: Content: Eval Phase */
$handler->display->display_options['fields']['field_eval_phase']['id'] = 'field_eval_phase';
$handler->display->display_options['fields']['field_eval_phase']['table'] = 'field_data_field_eval_phase';
$handler->display->display_options['fields']['field_eval_phase']['field'] = 'field_eval_phase';
$handler->display->display_options['fields']['field_eval_phase']['label'] = 'status';
/* Field: Content: Published status */
$handler->display->display_options['fields']['status']['id'] = 'status';
$handler->display->display_options['fields']['status']['table'] = 'node';
$handler->display->display_options['fields']['status']['field'] = 'status';
$handler->display->display_options['fields']['status']['label'] = 'status';
$handler->display->display_options['fields']['status']['not'] = 0;
/* Field: Content: Post date */
$handler->display->display_options['fields']['created']['id'] = 'created';
$handler->display->display_options['fields']['created']['table'] = 'node';
$handler->display->display_options['fields']['created']['field'] = 'created';
$handler->display->display_options['fields']['created']['label'] = 'created';
$handler->display->display_options['fields']['created']['date_format'] = 'standard';
$handler->display->display_options['fields']['created']['second_date_format'] = 'long';
$handler->display->display_options['fields']['created']['timezone'] = 'America/New_York';
/* Field: Content: Eval Assignee */
$handler->display->display_options['fields']['field_eval_assignee']['id'] = 'field_eval_assignee';
$handler->display->display_options['fields']['field_eval_assignee']['table'] = 'field_data_field_eval_assignee';
$handler->display->display_options['fields']['field_eval_assignee']['field'] = 'field_eval_assignee';
$handler->display->display_options['fields']['field_eval_assignee']['label'] = 'assignee';
$handler->display->display_options['fields']['field_eval_assignee']['type'] = 'user_reference_uid';
/* Field: Content: Eval Dept Equiv */
$handler->display->display_options['fields']['field_eval_dept_equiv']['id'] = 'field_eval_dept_equiv';
$handler->display->display_options['fields']['field_eval_dept_equiv']['table'] = 'field_data_field_eval_dept_equiv';
$handler->display->display_options['fields']['field_eval_dept_equiv']['field'] = 'field_eval_dept_equiv';
$handler->display->display_options['fields']['field_eval_dept_equiv']['label'] = 'draft_equiv1_course';
/* Field: Content: Eval Dept Eq Hrs */
$handler->display->display_options['fields']['field_eval_dept_eq_hrs']['id'] = 'field_eval_dept_eq_hrs';
$handler->display->display_options['fields']['field_eval_dept_eq_hrs']['table'] = 'field_data_field_eval_dept_eq_hrs';
$handler->display->display_options['fields']['field_eval_dept_eq_hrs']['field'] = 'field_eval_dept_eq_hrs';
$handler->display->display_options['fields']['field_eval_dept_eq_hrs']['label'] = 'draft_equiv1_credit_hrs';
/* Field: Content: Eval Dept Equiv Operator */
$handler->display->display_options['fields']['field_eval_dept_equiv_operator']['id'] = 'field_eval_dept_equiv_operator';
$handler->display->display_options['fields']['field_eval_dept_equiv_operator']['table'] = 'field_data_field_eval_dept_equiv_operator';
$handler->display->display_options['fields']['field_eval_dept_equiv_operator']['field'] = 'field_eval_dept_equiv_operator';
$handler->display->display_options['fields']['field_eval_dept_equiv_operator']['label'] = 'draft_equiv1_operator';
/* Field: Content: Eval Dept Equiv 2 */
$handler->display->display_options['fields']['field_eval_dept_equiv_2']['id'] = 'field_eval_dept_equiv_2';
$handler->display->display_options['fields']['field_eval_dept_equiv_2']['table'] = 'field_data_field_eval_dept_equiv_2';
$handler->display->display_options['fields']['field_eval_dept_equiv_2']['field'] = 'field_eval_dept_equiv_2';
$handler->display->display_options['fields']['field_eval_dept_equiv_2']['label'] = 'draft_equiv2_course';
/* Field: Content: Eval Dept Eq 2 Hrs */
$handler->display->display_options['fields']['field_eval_dept_eq_2_hrs']['id'] = 'field_eval_dept_eq_2_hrs';
$handler->display->display_options['fields']['field_eval_dept_eq_2_hrs']['table'] = 'field_data_field_eval_dept_eq_2_hrs';
$handler->display->display_options['fields']['field_eval_dept_eq_2_hrs']['field'] = 'field_eval_dept_eq_2_hrs';
$handler->display->display_options['fields']['field_eval_dept_eq_2_hrs']['label'] = 'draft_equiv2_credit_hrs';
/* Field: Content: Eval Dept Equiv 2 Operator */
$handler->display->display_options['fields']['field_eval_dept_equiv_2_operator']['id'] = 'field_eval_dept_equiv_2_operator';
$handler->display->display_options['fields']['field_eval_dept_equiv_2_operator']['table'] = 'field_data_field_eval_dept_equiv_2_operator';
$handler->display->display_options['fields']['field_eval_dept_equiv_2_operator']['field'] = 'field_eval_dept_equiv_2_operator';
$handler->display->display_options['fields']['field_eval_dept_equiv_2_operator']['label'] = 'draft_equiv2_operator';
/* Field: Content: Eval Dept Equiv 3 */
$handler->display->display_options['fields']['field_eval_dept_equiv_3']['id'] = 'field_eval_dept_equiv_3';
$handler->display->display_options['fields']['field_eval_dept_equiv_3']['table'] = 'field_data_field_eval_dept_equiv_3';
$handler->display->display_options['fields']['field_eval_dept_equiv_3']['field'] = 'field_eval_dept_equiv_3';
$handler->display->display_options['fields']['field_eval_dept_equiv_3']['label'] = 'draft_equiv3_course';
/* Field: Content: Eval Dept Eq 3 Hrs */
$handler->display->display_options['fields']['field_eval_dept_eq_3_hrs']['id'] = 'field_eval_dept_eq_3_hrs';
$handler->display->display_options['fields']['field_eval_dept_eq_3_hrs']['table'] = 'field_data_field_eval_dept_eq_3_hrs';
$handler->display->display_options['fields']['field_eval_dept_eq_3_hrs']['field'] = 'field_eval_dept_eq_3_hrs';
$handler->display->display_options['fields']['field_eval_dept_eq_3_hrs']['label'] = 'draft_equiv3_credit_hrs';
/* Field: Content: Eval Dept Equiv 3 Operator */
$handler->display->display_options['fields']['field_eval_dept_equiv_3_operator']['id'] = 'field_eval_dept_equiv_3_operator';
$handler->display->display_options['fields']['field_eval_dept_equiv_3_operator']['table'] = 'field_data_field_eval_dept_equiv_3_operator';
$handler->display->display_options['fields']['field_eval_dept_equiv_3_operator']['field'] = 'field_eval_dept_equiv_3_operator';
$handler->display->display_options['fields']['field_eval_dept_equiv_3_operator']['label'] = 'draft_equiv3_operator';
/* Field: Content: Eval Dept Equiv 4 */
$handler->display->display_options['fields']['field_eval_dept_equiv_4']['id'] = 'field_eval_dept_equiv_4';
$handler->display->display_options['fields']['field_eval_dept_equiv_4']['table'] = 'field_data_field_eval_dept_equiv_4';
$handler->display->display_options['fields']['field_eval_dept_equiv_4']['field'] = 'field_eval_dept_equiv_4';
$handler->display->display_options['fields']['field_eval_dept_equiv_4']['label'] = 'draft_equiv4_course';
/* Field: Content: Eval Dept Eq 4 Hrs */
$handler->display->display_options['fields']['field_eval_dept_eq_4_hrs']['id'] = 'field_eval_dept_eq_4_hrs';
$handler->display->display_options['fields']['field_eval_dept_eq_4_hrs']['table'] = 'field_data_field_eval_dept_eq_4_hrs';
$handler->display->display_options['fields']['field_eval_dept_eq_4_hrs']['field'] = 'field_eval_dept_eq_4_hrs';
$handler->display->display_options['fields']['field_eval_dept_eq_4_hrs']['label'] = 'draft_equiv4_credit_hrs';
/* Field: Content: Eval Dept Equiv Policy */
$handler->display->display_options['fields']['field_eval_dept_equiv_policy']['id'] = 'field_eval_dept_equiv_policy';
$handler->display->display_options['fields']['field_eval_dept_equiv_policy']['table'] = 'field_data_field_eval_dept_equiv_policy';
$handler->display->display_options['fields']['field_eval_dept_equiv_policy']['field'] = 'field_eval_dept_equiv_policy';
$handler->display->display_options['fields']['field_eval_dept_equiv_policy']['label'] = 'draft_policy';
/* Field: Content: Eval GT Equiv */
$handler->display->display_options['fields']['field_eval_gt_equiv']['id'] = 'field_eval_gt_equiv';
$handler->display->display_options['fields']['field_eval_gt_equiv']['table'] = 'field_data_field_eval_gt_equiv';
$handler->display->display_options['fields']['field_eval_gt_equiv']['field'] = 'field_eval_gt_equiv';
$handler->display->display_options['fields']['field_eval_gt_equiv']['label'] = 'final_equiv1_course';
$handler->display->display_options['fields']['field_eval_gt_equiv']['type'] = 'node_reference_nid';
/* Field: Content: Eval GT Eq Hrs */
$handler->display->display_options['fields']['field_eval_gt_eq_hrs_1']['id'] = 'field_eval_gt_eq_hrs_1';
$handler->display->display_options['fields']['field_eval_gt_eq_hrs_1']['table'] = 'field_data_field_eval_gt_eq_hrs';
$handler->display->display_options['fields']['field_eval_gt_eq_hrs_1']['field'] = 'field_eval_gt_eq_hrs';
$handler->display->display_options['fields']['field_eval_gt_eq_hrs_1']['label'] = 'final_equiv1_credit_hrs';
/* Field: Content: Eval GT Equiv Operator */
$handler->display->display_options['fields']['field_eval_gt_equiv_operator_1']['id'] = 'field_eval_gt_equiv_operator_1';
$handler->display->display_options['fields']['field_eval_gt_equiv_operator_1']['table'] = 'field_data_field_eval_gt_equiv_operator';
$handler->display->display_options['fields']['field_eval_gt_equiv_operator_1']['field'] = 'field_eval_gt_equiv_operator';
$handler->display->display_options['fields']['field_eval_gt_equiv_operator_1']['label'] = 'final_equiv1_operator';
/* Field: Content: Eval GT Equiv 2 */
$handler->display->display_options['fields']['field_eval_gt_equiv_2']['id'] = 'field_eval_gt_equiv_2';
$handler->display->display_options['fields']['field_eval_gt_equiv_2']['table'] = 'field_data_field_eval_gt_equiv_2';
$handler->display->display_options['fields']['field_eval_gt_equiv_2']['field'] = 'field_eval_gt_equiv_2';
$handler->display->display_options['fields']['field_eval_gt_equiv_2']['label'] = 'final_equiv2_course';
$handler->display->display_options['fields']['field_eval_gt_equiv_2']['type'] = 'node_reference_nid';
/* Field: Content: Eval GT Eq 2 Hrs */
$handler->display->display_options['fields']['field_eval_gt_eq_2_hrs']['id'] = 'field_eval_gt_eq_2_hrs';
$handler->display->display_options['fields']['field_eval_gt_eq_2_hrs']['table'] = 'field_data_field_eval_gt_eq_2_hrs';
$handler->display->display_options['fields']['field_eval_gt_eq_2_hrs']['field'] = 'field_eval_gt_eq_2_hrs';
$handler->display->display_options['fields']['field_eval_gt_eq_2_hrs']['label'] = 'final_equiv2_credit_hrs';
/* Field: Content: Eval GT Equiv 2 Operator */
$handler->display->display_options['fields']['field_eval_gt_equiv_2_operator']['id'] = 'field_eval_gt_equiv_2_operator';
$handler->display->display_options['fields']['field_eval_gt_equiv_2_operator']['table'] = 'field_data_field_eval_gt_equiv_2_operator';
$handler->display->display_options['fields']['field_eval_gt_equiv_2_operator']['field'] = 'field_eval_gt_equiv_2_operator';
$handler->display->display_options['fields']['field_eval_gt_equiv_2_operator']['label'] = 'final_equiv2_operator';
/* Field: Content: Eval GT Equiv 3 */
$handler->display->display_options['fields']['field_eval_gt_equiv_3']['id'] = 'field_eval_gt_equiv_3';
$handler->display->display_options['fields']['field_eval_gt_equiv_3']['table'] = 'field_data_field_eval_gt_equiv_3';
$handler->display->display_options['fields']['field_eval_gt_equiv_3']['field'] = 'field_eval_gt_equiv_3';
$handler->display->display_options['fields']['field_eval_gt_equiv_3']['label'] = 'final_equiv3_course';
$handler->display->display_options['fields']['field_eval_gt_equiv_3']['type'] = 'node_reference_nid';
/* Field: Content: Eval GT Eq 3 Hrs */
$handler->display->display_options['fields']['field_eval_gt_eq_3_hrs']['id'] = 'field_eval_gt_eq_3_hrs';
$handler->display->display_options['fields']['field_eval_gt_eq_3_hrs']['table'] = 'field_data_field_eval_gt_eq_3_hrs';
$handler->display->display_options['fields']['field_eval_gt_eq_3_hrs']['field'] = 'field_eval_gt_eq_3_hrs';
$handler->display->display_options['fields']['field_eval_gt_eq_3_hrs']['label'] = 'final_equiv3_credit_hrs';
/* Field: Content: Eval GT Equiv 3 Operator */
$handler->display->display_options['fields']['field_eval_gt_equiv_3_operator']['id'] = 'field_eval_gt_equiv_3_operator';
$handler->display->display_options['fields']['field_eval_gt_equiv_3_operator']['table'] = 'field_data_field_eval_gt_equiv_3_operator';
$handler->display->display_options['fields']['field_eval_gt_equiv_3_operator']['field'] = 'field_eval_gt_equiv_3_operator';
$handler->display->display_options['fields']['field_eval_gt_equiv_3_operator']['label'] = 'final_equiv3_operator';
/* Field: Content: Eval GT Equiv 4 */
$handler->display->display_options['fields']['field_eval_gt_equiv_4']['id'] = 'field_eval_gt_equiv_4';
$handler->display->display_options['fields']['field_eval_gt_equiv_4']['table'] = 'field_data_field_eval_gt_equiv_4';
$handler->display->display_options['fields']['field_eval_gt_equiv_4']['field'] = 'field_eval_gt_equiv_4';
$handler->display->display_options['fields']['field_eval_gt_equiv_4']['label'] = 'final_equiv4_course';
$handler->display->display_options['fields']['field_eval_gt_equiv_4']['type'] = 'node_reference_nid';
/* Field: Content: Eval GT Eq 4 Hrs */
$handler->display->display_options['fields']['field_eval_gt_eq_4_hrs']['id'] = 'field_eval_gt_eq_4_hrs';
$handler->display->display_options['fields']['field_eval_gt_eq_4_hrs']['table'] = 'field_data_field_eval_gt_eq_4_hrs';
$handler->display->display_options['fields']['field_eval_gt_eq_4_hrs']['field'] = 'field_eval_gt_eq_4_hrs';
$handler->display->display_options['fields']['field_eval_gt_eq_4_hrs']['label'] = 'final_equiv4_credit_hrs';
/* Field: Content: Eval GT Equiv Policy */
$handler->display->display_options['fields']['field_eval_gt_equiv_policy']['id'] = 'field_eval_gt_equiv_policy';
$handler->display->display_options['fields']['field_eval_gt_equiv_policy']['table'] = 'field_data_field_eval_gt_equiv_policy';
$handler->display->display_options['fields']['field_eval_gt_equiv_policy']['field'] = 'field_eval_gt_equiv_policy';
$handler->display->display_options['fields']['field_eval_gt_equiv_policy']['label'] = 'final_policy';
/* Field: Content: Eval Person Type */
$handler->display->display_options['fields']['field_eval_person_type']['id'] = 'field_eval_person_type';
$handler->display->display_options['fields']['field_eval_person_type']['table'] = 'field_data_field_eval_person_type';
$handler->display->display_options['fields']['field_eval_person_type']['field'] = 'field_eval_person_type';
$handler->display->display_options['fields']['field_eval_person_type']['label'] = 'requester_type';
/* Field: Content: Eval Course Entered Banner */
$handler->display->display_options['fields']['field_eval_course_entered_banner']['id'] = 'field_eval_course_entered_banner';
$handler->display->display_options['fields']['field_eval_course_entered_banner']['table'] = 'field_data_field_eval_course_entered_banner';
$handler->display->display_options['fields']['field_eval_course_entered_banner']['field'] = 'field_eval_course_entered_banner';
$handler->display->display_options['fields']['field_eval_course_entered_banner']['label'] = 'course_in_sis';
/* Field: Content: Eval Transcript Received */
$handler->display->display_options['fields']['field_eval_transcript_received']['id'] = 'field_eval_transcript_received';
$handler->display->display_options['fields']['field_eval_transcript_received']['table'] = 'field_data_field_eval_transcript_received';
$handler->display->display_options['fields']['field_eval_transcript_received']['field'] = 'field_eval_transcript_received';
$handler->display->display_options['fields']['field_eval_transcript_received']['label'] = 'transcript_on_hand';
/* Field: Content: Eval Hold for Acceptance */
$handler->display->display_options['fields']['field_eval_hold_for_acceptance']['id'] = 'field_eval_hold_for_acceptance';
$handler->display->display_options['fields']['field_eval_hold_for_acceptance']['table'] = 'field_data_field_eval_hold_for_acceptance';
$handler->display->display_options['fields']['field_eval_hold_for_acceptance']['field'] = 'field_eval_hold_for_acceptance';
$handler->display->display_options['fields']['field_eval_hold_for_acceptance']['label'] = 'hold_for_requester_admit';
/* Field: Content: Eval Hold for Course Input */
$handler->display->display_options['fields']['field_eval_hold_for_course_input']['id'] = 'field_eval_hold_for_course_input';
$handler->display->display_options['fields']['field_eval_hold_for_course_input']['table'] = 'field_data_field_eval_hold_for_course_input';
$handler->display->display_options['fields']['field_eval_hold_for_course_input']['field'] = 'field_eval_hold_for_course_input';
$handler->display->display_options['fields']['field_eval_hold_for_course_input']['label'] = 'hold_for_course_input';
/* Field: Content: Eval Hold for Transcript */
$handler->display->display_options['fields']['field_eval_hold_for_transcript']['id'] = 'field_eval_hold_for_transcript';
$handler->display->display_options['fields']['field_eval_hold_for_transcript']['table'] = 'field_data_field_eval_hold_for_transcript';
$handler->display->display_options['fields']['field_eval_hold_for_transcript']['field'] = 'field_eval_hold_for_transcript';
$handler->display->display_options['fields']['field_eval_hold_for_transcript']['label'] = 'hold_for_transcript';
/* Field: Content: Eval Tag Spot Articulated */
$handler->display->display_options['fields']['field_eval_tag_spot_articulated']['id'] = 'field_eval_tag_spot_articulated';
$handler->display->display_options['fields']['field_eval_tag_spot_articulated']['table'] = 'field_data_field_eval_tag_spot_articulated';
$handler->display->display_options['fields']['field_eval_tag_spot_articulated']['field'] = 'field_eval_tag_spot_articulated';
$handler->display->display_options['fields']['field_eval_tag_spot_articulated']['label'] = 'tag_spot_articulated';
/* Field: Content: Eval Tag R1 Back to Std */
$handler->display->display_options['fields']['field_eval_tag_r1_back_to_std']['id'] = 'field_eval_tag_r1_back_to_std';
$handler->display->display_options['fields']['field_eval_tag_r1_back_to_std']['table'] = 'field_data_field_eval_tag_r1_back_to_std';
$handler->display->display_options['fields']['field_eval_tag_r1_back_to_std']['field'] = 'field_eval_tag_r1_back_to_std';
$handler->display->display_options['fields']['field_eval_tag_r1_back_to_std']['label'] = 'tag_r1_to_student';
/* Field: Content: Eval Tag Dept Back to Std */
$handler->display->display_options['fields']['field_eval_tag_dept_back_to_std']['id'] = 'field_eval_tag_dept_back_to_std';
$handler->display->display_options['fields']['field_eval_tag_dept_back_to_std']['table'] = 'field_data_field_eval_tag_dept_back_to_std';
$handler->display->display_options['fields']['field_eval_tag_dept_back_to_std']['field'] = 'field_eval_tag_dept_back_to_std';
$handler->display->display_options['fields']['field_eval_tag_dept_back_to_std']['label'] = 'tag_dept_to_student';
/* Field: Content: Eval Tag Dept Back to R1 */
$handler->display->display_options['fields']['field_eval_tag_dept_back_to_r1']['id'] = 'field_eval_tag_dept_back_to_r1';
$handler->display->display_options['fields']['field_eval_tag_dept_back_to_r1']['table'] = 'field_data_field_eval_tag_dept_back_to_r1';
$handler->display->display_options['fields']['field_eval_tag_dept_back_to_r1']['field'] = 'field_eval_tag_dept_back_to_r1';
$handler->display->display_options['fields']['field_eval_tag_dept_back_to_r1']['label'] = 'tag_dept_to_r1';
/* Field: Content: Eval Tag R2 Back to Std */
$handler->display->display_options['fields']['field_eval_tag_r2_back_to_std']['id'] = 'field_eval_tag_r2_back_to_std';
$handler->display->display_options['fields']['field_eval_tag_r2_back_to_std']['table'] = 'field_data_field_eval_tag_r2_back_to_std';
$handler->display->display_options['fields']['field_eval_tag_r2_back_to_std']['field'] = 'field_eval_tag_r2_back_to_std';
$handler->display->display_options['fields']['field_eval_tag_r2_back_to_std']['label'] = 'tag_r2_to_student';
/* Field: Content: Eval Tag R2 Back to Dept */
$handler->display->display_options['fields']['field_eval_tag_r2_back_to_dept']['id'] = 'field_eval_tag_r2_back_to_dept';
$handler->display->display_options['fields']['field_eval_tag_r2_back_to_dept']['table'] = 'field_data_field_eval_tag_r2_back_to_dept';
$handler->display->display_options['fields']['field_eval_tag_r2_back_to_dept']['field'] = 'field_eval_tag_r2_back_to_dept';
$handler->display->display_options['fields']['field_eval_tag_r2_back_to_dept']['label'] = 'tag_r2_to_dept';
/* Field: Content: Eval Tag Dept to Dept */
$handler->display->display_options['fields']['field_eval_tag_dept_to_dept']['id'] = 'field_eval_tag_dept_to_dept';
$handler->display->display_options['fields']['field_eval_tag_dept_to_dept']['table'] = 'field_data_field_eval_tag_dept_to_dept';
$handler->display->display_options['fields']['field_eval_tag_dept_to_dept']['field'] = 'field_eval_tag_dept_to_dept';
$handler->display->display_options['fields']['field_eval_tag_dept_to_dept']['label'] = 'tag_reassigned';
/* Field: Content: Node UUID */
$handler->display->display_options['fields']['uuid']['id'] = 'uuid';
$handler->display->display_options['fields']['uuid']['table'] = 'node';
$handler->display->display_options['fields']['uuid']['field'] = 'uuid';
$handler->display->display_options['fields']['uuid']['label'] = 'uuid';
/* Field: Content: Updated date */
$handler->display->display_options['fields']['changed']['id'] = 'changed';
$handler->display->display_options['fields']['changed']['table'] = 'node';
$handler->display->display_options['fields']['changed']['field'] = 'changed';
$handler->display->display_options['fields']['changed']['label'] = 'Updated';
$handler->display->display_options['fields']['changed']['date_format'] = 'standard';
$handler->display->display_options['fields']['changed']['second_date_format'] = 'long';
/* Field: Content: Eval Course Title */
$handler->display->display_options['fields']['field_eval_course_title']['id'] = 'field_eval_course_title';
$handler->display->display_options['fields']['field_eval_course_title']['table'] = 'field_data_field_eval_course_title';
$handler->display->display_options['fields']['field_eval_course_title']['field'] = 'field_eval_course_title';
$handler->display->display_options['fields']['field_eval_course_title']['label'] = 'course_title';
/* Field: Content: Eval Lab Course Title */
$handler->display->display_options['fields']['field_eval_lab_course_title']['id'] = 'field_eval_lab_course_title';
$handler->display->display_options['fields']['field_eval_lab_course_title']['table'] = 'field_data_field_eval_lab_course_title';
$handler->display->display_options['fields']['field_eval_lab_course_title']['field'] = 'field_eval_lab_course_title';
$handler->display->display_options['fields']['field_eval_lab_course_title']['label'] = 'lab_title';
/* Sort criterion: Content: Eval Serial Number (field_eval_serial_number) */
$handler->display->display_options['sorts']['field_eval_serial_number_value']['id'] = 'field_eval_serial_number_value';
$handler->display->display_options['sorts']['field_eval_serial_number_value']['table'] = 'field_data_field_eval_serial_number';
$handler->display->display_options['sorts']['field_eval_serial_number_value']['field'] = 'field_eval_serial_number_value';
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
  'evaluation' => 'evaluation',
);
/* Filter criterion: Content: Eval Serial Number (field_eval_serial_number) */
$handler->display->display_options['filters']['field_eval_serial_number_value']['id'] = 'field_eval_serial_number_value';
$handler->display->display_options['filters']['field_eval_serial_number_value']['table'] = 'field_data_field_eval_serial_number';
$handler->display->display_options['filters']['field_eval_serial_number_value']['field'] = 'field_eval_serial_number_value';
$handler->display->display_options['filters']['field_eval_serial_number_value']['operator'] = 'not empty';

/* Display: Data export-10000 */
$handler = $view->new_display('views_data_export', 'Data export-10000', 'views_data_export_1');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '500';
$handler->display->display_options['pager']['options']['offset'] = '10000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'evaluations-10000.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-10500 */
$handler = $view->new_display('views_data_export', 'Data export-10500', 'views_data_export_2');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '500';
$handler->display->display_options['pager']['options']['offset'] = '10500';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'evaluations-10500.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-11000 */
$handler = $view->new_display('views_data_export', 'Data export-11000', 'views_data_export_3');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '500';
$handler->display->display_options['pager']['options']['offset'] = '11000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'evaluations-11000.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-11500 */
$handler = $view->new_display('views_data_export', 'Data export-11500', 'views_data_export_4');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '500';
$handler->display->display_options['pager']['options']['offset'] = '11500';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'evaluations-11500.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-12000 */
$handler = $view->new_display('views_data_export', 'Data export-12000', 'views_data_export_5');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '500';
$handler->display->display_options['pager']['options']['offset'] = '12000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'evaluations-12000.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-12500 */
$handler = $view->new_display('views_data_export', 'Data export-12500', 'views_data_export_6');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '500';
$handler->display->display_options['pager']['options']['offset'] = '12500';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'evaluations-12500.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-13000 */
$handler = $view->new_display('views_data_export', 'Data export-13000', 'views_data_export_7');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '500';
$handler->display->display_options['pager']['options']['offset'] = '13000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'evaluations-13000.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-13500 */
$handler = $view->new_display('views_data_export', 'Data export-13500', 'views_data_export_8');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '500';
$handler->display->display_options['pager']['options']['offset'] = '13500';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'evaluations-13500.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-14000 */
$handler = $view->new_display('views_data_export', 'Data export-14000', 'views_data_export_9');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '500';
$handler->display->display_options['pager']['options']['offset'] = '14000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'evaluations-14000.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-14500 */
$handler = $view->new_display('views_data_export', 'Data export-14500', 'views_data_export_10');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '500';
$handler->display->display_options['pager']['options']['offset'] = '14500';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'evaluations-14500.csv';
$handler->display->display_options['sitename_title'] = 0;
