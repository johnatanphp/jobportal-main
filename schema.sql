-- PostgreSQL Schema for Portal de Empleo
-- Generated from model/controller analysis

-- App config table
CREATE TABLE IF NOT EXISTS tbl_app_config (
    id SERIAL PRIMARY KEY,
    "key" VARCHAR(255) NOT NULL UNIQUE,
    value TEXT
);

-- Admin table
CREATE TABLE IF NOT EXISTS tbl_admin (
    id SERIAL PRIMARY KEY,
    admin_username VARCHAR(255) NOT NULL,
    admin_password VARCHAR(255) NOT NULL,
    admin_name VARCHAR(255),
    admin_email VARCHAR(255),
    profile_photo VARCHAR(255),
    created_at TIMESTAMP DEFAULT NOW()
);

-- Logs table
CREATE TABLE IF NOT EXISTS tbl_logs (
    id SERIAL PRIMARY KEY,
    type VARCHAR(100),
    message TEXT,
    created_at TIMESTAMP DEFAULT NOW(),
    server_data TEXT
);

-- Countries table
CREATE TABLE IF NOT EXISTS tbl_countries (
    "ID" SERIAL PRIMARY KEY,
    country_name VARCHAR(255) NOT NULL,
    iso_3166_1_alpha2 VARCHAR(10),
    iso_3166_1_alpha3 VARCHAR(10),
    flag_icon VARCHAR(255),
    currency VARCHAR(50),
    currency_symbol VARCHAR(20),
    phone_code VARCHAR(20),
    has_operation_overall SMALLINT DEFAULT 0,
    active SMALLINT DEFAULT 1
);

-- Cities table
CREATE TABLE IF NOT EXISTS tbl_cities (
    "ID" SERIAL PRIMARY KEY,
    city_name VARCHAR(255) NOT NULL,
    country_id INTEGER,
    sort_order INTEGER DEFAULT 0,
    "show" SMALLINT DEFAULT 1,
    is_popular VARCHAR(10) DEFAULT 'no',
    active SMALLINT DEFAULT 1
);

-- Job industries table
CREATE TABLE IF NOT EXISTS tbl_job_industries (
    "ID" SERIAL PRIMARY KEY,
    industry_name VARCHAR(255) NOT NULL,
    active SMALLINT DEFAULT 1
);

-- Companies table
CREATE TABLE IF NOT EXISTS tbl_companies (
    "ID" SERIAL PRIMARY KEY,
    company_ruc VARCHAR(50),
    company_name VARCHAR(255) NOT NULL,
    "industry_ID" INTEGER,
    company_phone VARCHAR(100),
    country_id INTEGER,
    company_country VARCHAR(255),
    company_city VARCHAR(255),
    company_location TEXT,
    company_website VARCHAR(255),
    no_of_employees VARCHAR(50),
    company_description TEXT,
    company_logo VARCHAR(255),
    company_banner VARCHAR(255),
    company_slug VARCHAR(255),
    ownership_type VARCHAR(100),
    is_verified SMALLINT DEFAULT 0,
    active SMALLINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Employers table
CREATE TABLE IF NOT EXISTS tbl_employers (
    "ID" SERIAL PRIMARY KEY,
    full_name VARCHAR(255),
    email VARCHAR(255) NOT NULL,
    pass_code VARCHAR(255),
    mobile_phone VARCHAR(100),
    "company_ID" INTEGER,
    country_id INTEGER,
    city VARCHAR(255),
    is_admin SMALLINT DEFAULT 0,
    profile_photo VARCHAR(255),
    is_active SMALLINT DEFAULT 1,
    is_deleted SMALLINT DEFAULT 0,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

-- Employer profiles table
CREATE TABLE IF NOT EXISTS tbl_employer_profiles (
    "ID" SERIAL PRIMARY KEY,
    "employer_ID" INTEGER,
    role VARCHAR(100),
    permissions TEXT,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Employer RRHH types
CREATE TABLE IF NOT EXISTS tbl_employer_rrhh_types (
    "ID" SERIAL PRIMARY KEY,
    name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Job seekers table
CREATE TABLE IF NOT EXISTS tbl_job_seekers (
    "ID" SERIAL PRIMARY KEY,
    first_name VARCHAR(255),
    paternal_last_name VARCHAR(255),
    maternal_last_name VARCHAR(255),
    last_name VARCHAR(255),
    document_number VARCHAR(50),
    document_type INTEGER,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255),
    dob DATE,
    mobile VARCHAR(100),
    home_phone VARCHAR(100),
    country INTEGER,
    city VARCHAR(255),
    nationality INTEGER,
    gender INTEGER,
    ip_address VARCHAR(50),
    dated TIMESTAMP DEFAULT NOW(),
    civil_status INTEGER,
    disability INTEGER,
    facebook VARCHAR(255),
    linkedin VARCHAR(255),
    twitter VARCHAR(255),
    profile_photo VARCHAR(255),
    cv_file VARCHAR(255),
    is_active SMALLINT DEFAULT 1,
    is_verified SMALLINT DEFAULT 0,
    ubigeo_code VARCHAR(50),
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

-- Jobseeker additional info
CREATE TABLE IF NOT EXISTS tbl_jobseeker_additional_info (
    "ID" SERIAL PRIMARY KEY,
    "seeker_ID" INTEGER,
    about_me TEXT,
    resume_title VARCHAR(255),
    expected_salary VARCHAR(100),
    current_salary VARCHAR(100),
    career_level VARCHAR(100),
    industry_id INTEGER,
    functional_area_id INTEGER,
    job_type VARCHAR(100),
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

-- Seeker config
CREATE TABLE IF NOT EXISTS tbl_seeker_config (
    id SERIAL PRIMARY KEY,
    "key" VARCHAR(255),
    value TEXT,
    "seeker_ID" INTEGER
);

-- Seeker legal terms
CREATE TABLE IF NOT EXISTS tbl_seeker_legal_terms (
    id SERIAL PRIMARY KEY,
    "seeker_ID" INTEGER,
    accepted SMALLINT DEFAULT 0,
    accepted_at TIMESTAMP DEFAULT NOW()
);

-- Qualifications table
CREATE TABLE IF NOT EXISTS tbl_qualifications (
    "ID" SERIAL PRIMARY KEY,
    qualification_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Posted jobs / tbl_post_jobs
CREATE TABLE IF NOT EXISTS tbl_post_jobs (
    "ID" SERIAL PRIMARY KEY,
    job_title VARCHAR(255),
    "company_ID" INTEGER,
    country_id INTEGER,
    city_id INTEGER,
    job_description TEXT,
    job_requirements TEXT,
    job_type VARCHAR(100),
    salary_from DECIMAL(10,2),
    salary_to DECIMAL(10,2),
    salary_currency VARCHAR(20),
    hide_salary SMALLINT DEFAULT 0,
    industry_id INTEGER,
    functional_area_id INTEGER,
    qualification_id INTEGER,
    career_level VARCHAR(100),
    job_status VARCHAR(50) DEFAULT 'open',
    featured SMALLINT DEFAULT 0,
    deadline DATE,
    posted_date TIMESTAMP DEFAULT NOW(),
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW(),
    is_active SMALLINT DEFAULT 1,
    vacancies INTEGER DEFAULT 1,
    job_slug VARCHAR(255),
    "employer_ID" INTEGER,
    has_questions VARCHAR(10) DEFAULT 'no',
    apply_type VARCHAR(50) DEFAULT 'online'
);

-- Job questions
CREATE TABLE IF NOT EXISTS tbl_job_questions (
    "ID" SERIAL PRIMARY KEY,
    question TEXT,
    type_question VARCHAR(100),
    value TEXT,
    required VARCHAR(10) DEFAULT 'no',
    "job_ID" INTEGER
);

-- Applied jobs
CREATE TABLE IF NOT EXISTS tbl_seeker_applied_for_job (
    "ID" SERIAL PRIMARY KEY,
    "seeker_ID" INTEGER,
    "job_ID" INTEGER,
    applied_date TIMESTAMP DEFAULT NOW(),
    cover_letter TEXT,
    status VARCHAR(50) DEFAULT 'pending',
    is_shortlisted SMALLINT DEFAULT 0,
    is_viewed SMALLINT DEFAULT 0
);

-- Applied job answer values
CREATE TABLE IF NOT EXISTS tbl_seeker_applied_job_answers_values (
    "ID" SERIAL PRIMARY KEY,
    answer_value TEXT,
    type_question VARCHAR(100),
    "question_ID" INTEGER,
    "applied_ID" INTEGER
);

-- Job alerts
CREATE TABLE IF NOT EXISTS tbl_job_alert_queue (
    "ID" SERIAL PRIMARY KEY,
    "seeker_ID" INTEGER,
    "job_ID" INTEGER,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Seeker skills
CREATE TABLE IF NOT EXISTS tbl_seeker_skills (
    "ID" SERIAL PRIMARY KEY,
    "seeker_ID" INTEGER,
    skill_name VARCHAR(255),
    created_at TIMESTAMP DEFAULT NOW()
);

-- Skills table
CREATE TABLE IF NOT EXISTS tbl_skills (
    "ID" SERIAL PRIMARY KEY,
    skill_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Newsletter
CREATE TABLE IF NOT EXISTS tbl_newsletter (
    "ID" SERIAL PRIMARY KEY,
    email VARCHAR(255),
    created_at TIMESTAMP DEFAULT NOW(),
    active SMALLINT DEFAULT 1
);

-- Ads table
CREATE TABLE IF NOT EXISTS tbl_ad_codes (
    "ID" SERIAL PRIMARY KEY,
    ad_code_top TEXT,
    ad_code_bottom TEXT,
    ad_code_left TEXT,
    ad_code_right TEXT,
    ad_banner_image VARCHAR(255),
    ad_banner_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT NOW()
);

-- Success stories
CREATE TABLE IF NOT EXISTS tbl_success_stories (
    "ID" SERIAL PRIMARY KEY,
    story_title VARCHAR(255),
    story_content TEXT,
    story_image VARCHAR(255),
    active SMALLINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT NOW()
);

-- CMS pages
CREATE TABLE IF NOT EXISTS tbl_cms (
    "ID" SERIAL PRIMARY KEY,
    page_title VARCHAR(255),
    page_content TEXT,
    page_slug VARCHAR(255),
    meta_title VARCHAR(255),
    meta_description TEXT,
    active SMALLINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Resume table
CREATE TABLE IF NOT EXISTS tbl_seekers_resume (
    "ID" SERIAL PRIMARY KEY,
    "seeker_ID" INTEGER,
    resume_file VARCHAR(255),
    created_at TIMESTAMP DEFAULT NOW()
);

-- Salaries
CREATE TABLE IF NOT EXISTS tbl_salaries (
    "ID" SERIAL PRIMARY KEY,
    "seeker_ID" INTEGER,
    current_salary VARCHAR(100),
    expected_salary VARCHAR(100),
    created_at TIMESTAMP DEFAULT NOW()
);

-- Work experience
CREATE TABLE IF NOT EXISTS tbl_work_experience (
    "ID" SERIAL PRIMARY KEY,
    "seeker_ID" INTEGER,
    job_title VARCHAR(255),
    company VARCHAR(255),
    start_date DATE,
    end_date DATE,
    is_current SMALLINT DEFAULT 0,
    description TEXT,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Institutes
CREATE TABLE IF NOT EXISTS tbl_institute (
    "ID" SERIAL PRIMARY KEY,
    institute_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Institutions
CREATE TABLE IF NOT EXISTS tbl_institutions (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Academic records
CREATE TABLE IF NOT EXISTS tbl_seekers_academic (
    "ID" SERIAL PRIMARY KEY,
    "seeker_ID" INTEGER,
    qualification_id INTEGER,
    institute_id INTEGER,
    title VARCHAR(255),
    start_date DATE,
    end_date DATE,
    is_current SMALLINT DEFAULT 0,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Other studies
CREATE TABLE IF NOT EXISTS tbl_seekers_other_studies (
    "ID" SERIAL PRIMARY KEY,
    "seeker_ID" INTEGER,
    study_title VARCHAR(255),
    institute VARCHAR(255),
    start_date DATE,
    end_date DATE,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Job seeker experience
CREATE TABLE IF NOT EXISTS tbl_seeker_experience (
    "ID" SERIAL PRIMARY KEY,
    "seeker_ID" INTEGER,
    job_title VARCHAR(255),
    company_name VARCHAR(255),
    start_date DATE,
    end_date DATE,
    is_current SMALLINT DEFAULT 0,
    description TEXT,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Email drafts
CREATE TABLE IF NOT EXISTS tbl_email_drafts (
    "ID" SERIAL PRIMARY KEY,
    email_type VARCHAR(100),
    subject VARCHAR(255),
    body TEXT,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Email log
CREATE TABLE IF NOT EXISTS tbl_email_log (
    "ID" SERIAL PRIMARY KEY,
    from_email VARCHAR(255),
    to_email VARCHAR(255),
    subject VARCHAR(255),
    body TEXT,
    sent_at TIMESTAMP DEFAULT NOW(),
    status VARCHAR(50)
);

-- Prohibited keywords
CREATE TABLE IF NOT EXISTS tbl_prohibited_keywords (
    "ID" SERIAL PRIMARY KEY,
    keyword VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Scam reports
CREATE TABLE IF NOT EXISTS tbl_scam_reports (
    "ID" SERIAL PRIMARY KEY,
    "job_ID" INTEGER,
    "seeker_ID" INTEGER,
    report_message TEXT,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Menu model
CREATE TABLE IF NOT EXISTS tbl_page_menu (
    "ID" SERIAL PRIMARY KEY,
    menu_name VARCHAR(255),
    menu_url VARCHAR(255),
    sort_order INTEGER DEFAULT 0,
    active SMALLINT DEFAULT 1
);

-- Menu pages
CREATE TABLE IF NOT EXISTS tbl_page_menu_pages (
    "ID" SERIAL PRIMARY KEY,
    "menu_ID" INTEGER,
    page_title VARCHAR(255),
    page_slug VARCHAR(255),
    sort_order INTEGER DEFAULT 0,
    active SMALLINT DEFAULT 1
);

-- Ubigeos (Peru geographic data)
CREATE TABLE IF NOT EXISTS tbl_ubigeos (
    id SERIAL PRIMARY KEY,
    code VARCHAR(20),
    order_administrative1 VARCHAR(100),
    order_administrative2 VARCHAR(100),
    order_administrative3 VARCHAR(100),
    department VARCHAR(100),
    province VARCHAR(100),
    district VARCHAR(100)
);

-- Civil status
CREATE TABLE IF NOT EXISTS tbl_civil_status (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100),
    country_id INTEGER,
    active SMALLINT DEFAULT 1
);

-- Disabilities
CREATE TABLE IF NOT EXISTS tbl_disabilities (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Genders
CREATE TABLE IF NOT EXISTS tbl_genders (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100),
    active SMALLINT DEFAULT 1
);

-- Identity document types
CREATE TABLE IF NOT EXISTS tbl_identity_document_types (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100),
    country_id INTEGER,
    active SMALLINT DEFAULT 1
);

-- Laboral benefits
CREATE TABLE IF NOT EXISTS tbl_laboral_benefits (
    "ID" SERIAL PRIMARY KEY,
    benefit_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- CAP (Cuadro de Asignación de Personal)
CREATE TABLE IF NOT EXISTS tbl_caps (
    "ID" SERIAL PRIMARY KEY,
    "company_ID" INTEGER,
    name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Business units
CREATE TABLE IF NOT EXISTS tbl_business_units (
    "ID" SERIAL PRIMARY KEY,
    "company_ID" INTEGER,
    business_unit_name VARCHAR(255),
    business_unit_code VARCHAR(100),
    active SMALLINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Cost center manager
CREATE TABLE IF NOT EXISTS tbl_cost_center_manager (
    "ID" SERIAL PRIMARY KEY,
    "employer_ID" INTEGER,
    "company_ID" INTEGER,
    cost_center_code VARCHAR(100),
    active SMALLINT DEFAULT 1
);

-- Job functional areas
CREATE TABLE IF NOT EXISTS tbl_job_functional_areas (
    "ID" SERIAL PRIMARY KEY,
    functional_area_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Job charges
CREATE TABLE IF NOT EXISTS tbl_job_charges (
    "ID" SERIAL PRIMARY KEY,
    "company_ID" INTEGER,
    charge_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Job competences
CREATE TABLE IF NOT EXISTS tbl_job_competences (
    "ID" SERIAL PRIMARY KEY,
    "company_ID" INTEGER,
    competence_name VARCHAR(255),
    type VARCHAR(100),
    active SMALLINT DEFAULT 1
);

-- Internal areas
CREATE TABLE IF NOT EXISTS tbl_internal_areas (
    "ID" SERIAL PRIMARY KEY,
    "company_ID" INTEGER,
    area_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Staff requests
CREATE TABLE IF NOT EXISTS tbl_staff_requests (
    "ID" SERIAL PRIMARY KEY,
    "company_ID" INTEGER,
    "employer_ID" INTEGER,
    job_title VARCHAR(255),
    request_type VARCHAR(100),
    status VARCHAR(50) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

-- Staff request authorities
CREATE TABLE IF NOT EXISTS tbl_staff_request_authorities (
    "ID" SERIAL PRIMARY KEY,
    "request_ID" INTEGER,
    "employer_ID" INTEGER,
    authority_type VARCHAR(100),
    active SMALLINT DEFAULT 1
);

-- Staff request authorizations
CREATE TABLE IF NOT EXISTS tbl_staff_request_authorizations (
    "ID" SERIAL PRIMARY KEY,
    "request_ID" INTEGER,
    "employer_ID" INTEGER,
    status VARCHAR(50),
    created_at TIMESTAMP DEFAULT NOW()
);

-- Recruitment processes
CREATE TABLE IF NOT EXISTS tbl_recruitment_process (
    "ID" SERIAL PRIMARY KEY,
    "company_ID" INTEGER,
    "job_ID" INTEGER,
    process_name VARCHAR(255),
    status VARCHAR(50) DEFAULT 'active',
    created_at TIMESTAMP DEFAULT NOW()
);

-- Recruitment candidates
CREATE TABLE IF NOT EXISTS tbl_recruitment_candidates (
    "ID" SERIAL PRIMARY KEY,
    "process_ID" INTEGER,
    "seeker_ID" INTEGER,
    status VARCHAR(50) DEFAULT 'active',
    created_at TIMESTAMP DEFAULT NOW()
);

-- Recruitment stages
CREATE TABLE IF NOT EXISTS tbl_recruitment_stages (
    "ID" SERIAL PRIMARY KEY,
    "process_ID" INTEGER,
    stage_name VARCHAR(255),
    sort_order INTEGER DEFAULT 0,
    active SMALLINT DEFAULT 1
);

-- Workflow clients
CREATE TABLE IF NOT EXISTS tbl_workflow_clients (
    "ID" SERIAL PRIMARY KEY,
    code VARCHAR(100),
    name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Workflow consultants
CREATE TABLE IF NOT EXISTS tbl_workflow_consultants (
    "ID" SERIAL PRIMARY KEY,
    code VARCHAR(100),
    name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Workflow cost centers
CREATE TABLE IF NOT EXISTS tbl_workflow_cost_centers (
    "ID" SERIAL PRIMARY KEY,
    code VARCHAR(100),
    name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Employer permission business units
CREATE TABLE IF NOT EXISTS tbl_employer_permission_business_units (
    "ID" SERIAL PRIMARY KEY,
    "employer_ID" INTEGER,
    business_unit_code VARCHAR(100)
);

-- Employer permission clients
CREATE TABLE IF NOT EXISTS tbl_employer_permission_clients (
    "ID" SERIAL PRIMARY KEY,
    "employer_ID" INTEGER,
    client_code VARCHAR(100)
);

-- Employer permission consultants
CREATE TABLE IF NOT EXISTS tbl_employer_permission_consultants (
    "ID" SERIAL PRIMARY KEY,
    "employer_ID" INTEGER,
    consultant_code VARCHAR(100)
);

-- Employer permission cost centers
CREATE TABLE IF NOT EXISTS tbl_employer_permission_cost_centers (
    "ID" SERIAL PRIMARY KEY,
    "employer_ID" INTEGER,
    cost_center_code VARCHAR(100)
);

-- Employer permission internal areas
CREATE TABLE IF NOT EXISTS tbl_employer_permission_internal_areas (
    "ID" SERIAL PRIMARY KEY,
    "employer_ID" INTEGER,
    "area_ID" INTEGER
);

-- Employer permission job charges
CREATE TABLE IF NOT EXISTS tbl_employer_permission_job_charges (
    "ID" SERIAL PRIMARY KEY,
    "employer_ID" INTEGER,
    "charge_ID" INTEGER
);

-- Exam requests
CREATE TABLE IF NOT EXISTS tbl_exam_requests (
    "ID" SERIAL PRIMARY KEY,
    "company_ID" INTEGER,
    "employer_ID" INTEGER,
    exam_type VARCHAR(100),
    status VARCHAR(50) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT NOW()
);

-- Exam request seekers
CREATE TABLE IF NOT EXISTS tbl_exam_request_seekers (
    "ID" SERIAL PRIMARY KEY,
    "request_ID" INTEGER,
    "seeker_ID" INTEGER,
    status VARCHAR(50),
    created_at TIMESTAMP DEFAULT NOW()
);

-- Exam request seekers tmp
CREATE TABLE IF NOT EXISTS tbl_exam_request_seekers_tmp (
    "ID" SERIAL PRIMARY KEY,
    "request_ID" INTEGER,
    "seeker_ID" INTEGER,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Exam request schedules
CREATE TABLE IF NOT EXISTS tbl_exam_request_schedules (
    "ID" SERIAL PRIMARY KEY,
    "request_ID" INTEGER,
    scheduled_date DATE,
    notes TEXT,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Exam request types
CREATE TABLE IF NOT EXISTS tbl_exam_request_types (
    "ID" SERIAL PRIMARY KEY,
    type_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Exam request document types
CREATE TABLE IF NOT EXISTS tbl_exam_request_document_types (
    "ID" SERIAL PRIMARY KEY,
    type_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Exam request results
CREATE TABLE IF NOT EXISTS tbl_exam_request_results (
    "ID" SERIAL PRIMARY KEY,
    "request_ID" INTEGER,
    "seeker_ID" INTEGER,
    result VARCHAR(100),
    notes TEXT,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Exam request result types
CREATE TABLE IF NOT EXISTS tbl_exam_request_result_types (
    "ID" SERIAL PRIMARY KEY,
    type_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Exam request status
CREATE TABLE IF NOT EXISTS tbl_exam_request_status (
    "ID" SERIAL PRIMARY KEY,
    status_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Exam request overall emails
CREATE TABLE IF NOT EXISTS tbl_exam_request_overall_emails (
    "ID" SERIAL PRIMARY KEY,
    "request_ID" INTEGER,
    email VARCHAR(255),
    sent_at TIMESTAMP DEFAULT NOW()
);

-- Exam EMO types
CREATE TABLE IF NOT EXISTS tbl_exam_emo_types (
    "ID" SERIAL PRIMARY KEY,
    type_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Exam COVID19 types
CREATE TABLE IF NOT EXISTS tbl_exam_covid19_types (
    "ID" SERIAL PRIMARY KEY,
    type_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Entry form
CREATE TABLE IF NOT EXISTS tbl_entry_form (
    "ID" SERIAL PRIMARY KEY,
    "seeker_ID" INTEGER,
    "request_ID" INTEGER,
    form_data TEXT,
    status VARCHAR(50),
    created_at TIMESTAMP DEFAULT NOW()
);

-- Entry form EC
CREATE TABLE IF NOT EXISTS tbl_entry_form_ec (
    "ID" SERIAL PRIMARY KEY,
    "seeker_ID" INTEGER,
    "request_ID" INTEGER,
    form_data TEXT,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Entry form EC rightful claimants
CREATE TABLE IF NOT EXISTS tbl_entry_form_ec_rightful_claimants (
    "ID" SERIAL PRIMARY KEY,
    "entry_form_ID" INTEGER,
    name VARCHAR(255),
    created_at TIMESTAMP DEFAULT NOW()
);

-- Entry form MX
CREATE TABLE IF NOT EXISTS tbl_entry_form_mx (
    "ID" SERIAL PRIMARY KEY,
    "seeker_ID" INTEGER,
    "request_ID" INTEGER,
    form_data TEXT,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Entry form MX rightful claimants
CREATE TABLE IF NOT EXISTS tbl_entry_form_mx_rightful_claimants (
    "ID" SERIAL PRIMARY KEY,
    "entry_form_ID" INTEGER,
    name VARCHAR(255),
    created_at TIMESTAMP DEFAULT NOW()
);

-- Gantt activities
CREATE TABLE IF NOT EXISTS tbl_gantt_activities (
    "ID" SERIAL PRIMARY KEY,
    activity_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Gantt types
CREATE TABLE IF NOT EXISTS tbl_gantt_types (
    "ID" SERIAL PRIMARY KEY,
    type_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Factor types
CREATE TABLE IF NOT EXISTS tbl_factor_types (
    "ID" SERIAL PRIMARY KEY,
    type_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Factor valuations
CREATE TABLE IF NOT EXISTS tbl_factor_valuations (
    "ID" SERIAL PRIMARY KEY,
    "factor_type_ID" INTEGER,
    valuation_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Settings
CREATE TABLE IF NOT EXISTS tbl_settings (
    "ID" SERIAL PRIMARY KEY,
    "key" VARCHAR(255) NOT NULL UNIQUE,
    value TEXT
);

-- Banks
CREATE TABLE IF NOT EXISTS tbl_banks (
    "ID" SERIAL PRIMARY KEY,
    bank_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- HRM API connections
CREATE TABLE IF NOT EXISTS tbl_hrmgo_api_conections (
    "ID" SERIAL PRIMARY KEY,
    "company_ID" INTEGER,
    api_url VARCHAR(255),
    api_key VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- SUNAT codes
CREATE TABLE IF NOT EXISTS tbl_sunat_codes (
    "ID" SERIAL PRIMARY KEY,
    code VARCHAR(100),
    description VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- MOF codes
CREATE TABLE IF NOT EXISTS tbl_mof_codes (
    "ID" SERIAL PRIMARY KEY,
    code VARCHAR(100),
    description VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Job layout codes
CREATE TABLE IF NOT EXISTS tbl_job_layout_codes (
    "ID" SERIAL PRIMARY KEY,
    "company_ID" INTEGER,
    layout_code VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Job layout code integrations
CREATE TABLE IF NOT EXISTS tbl_job_layout_code_integrations (
    "ID" SERIAL PRIMARY KEY,
    "layout_code_ID" INTEGER,
    integration_type VARCHAR(100),
    active SMALLINT DEFAULT 1
);

-- Job profile codes
CREATE TABLE IF NOT EXISTS tbl_job_profile_codes (
    "ID" SERIAL PRIMARY KEY,
    "company_ID" INTEGER,
    profile_code VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Expense types
CREATE TABLE IF NOT EXISTS tbl_expense_types (
    "ID" SERIAL PRIMARY KEY,
    type_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Careers
CREATE TABLE IF NOT EXISTS tbl_careers (
    "ID" SERIAL PRIMARY KEY,
    career_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Institution careers
CREATE TABLE IF NOT EXISTS tbl_institution_careers (
    id SERIAL PRIMARY KEY,
    "institution_ID" INTEGER,
    career_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Company config
CREATE TABLE IF NOT EXISTS tbl_company_config (
    id SERIAL PRIMARY KEY,
    "company_ID" INTEGER,
    "key" VARCHAR(255),
    value TEXT
);

-- RYS forms
CREATE TABLE IF NOT EXISTS tbl_rys_forms (
    "ID" SERIAL PRIMARY KEY,
    "company_ID" INTEGER,
    form_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Employer staff request manage business units
CREATE TABLE IF NOT EXISTS tbl_employer_staff_request_manage_business_units (
    "ID" SERIAL PRIMARY KEY,
    "employer_ID" INTEGER,
    "business_unit_ID" INTEGER,
    active SMALLINT DEFAULT 1
);

-- Manage employer
CREATE TABLE IF NOT EXISTS tbl_manage_employer (
    "ID" SERIAL PRIMARY KEY,
    "employer_ID" INTEGER,
    "managed_employer_ID" INTEGER,
    active SMALLINT DEFAULT 1
);

-- SAP workflow consultants
CREATE TABLE IF NOT EXISTS tbl_sap_workflow_consultants (
    "ID" SERIAL PRIMARY KEY,
    code VARCHAR(100),
    name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Workflow areas
CREATE TABLE IF NOT EXISTS tbl_workflow_areas (
    "ID" SERIAL PRIMARY KEY,
    area_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Form RTP rightful claimants
CREATE TABLE IF NOT EXISTS tbl_form_rtps_rightful_claimants (
    "ID" SERIAL PRIMARY KEY,
    "entry_form_ID" INTEGER,
    name VARCHAR(255),
    created_at TIMESTAMP DEFAULT NOW()
);

-- Staff request computing
CREATE TABLE IF NOT EXISTS tbl_staff_request_computing (
    "ID" SERIAL PRIMARY KEY,
    "request_ID" INTEGER,
    data TEXT,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Staff request fixed competences
CREATE TABLE IF NOT EXISTS tbl_staff_request_fixed_competences (
    "ID" SERIAL PRIMARY KEY,
    "request_ID" INTEGER,
    "competence_ID" INTEGER,
    active SMALLINT DEFAULT 1
);

-- Staff request gantt
CREATE TABLE IF NOT EXISTS tbl_staff_request_gantt (
    "ID" SERIAL PRIMARY KEY,
    "request_ID" INTEGER,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Staff request gantt activities
CREATE TABLE IF NOT EXISTS tbl_staff_request_gantt_activities (
    "ID" SERIAL PRIMARY KEY,
    "request_ID" INTEGER,
    "activity_ID" INTEGER,
    active SMALLINT DEFAULT 1
);

-- Staff request job functions
CREATE TABLE IF NOT EXISTS tbl_staff_request_job_functions (
    "ID" SERIAL PRIMARY KEY,
    "request_ID" INTEGER,
    function_description TEXT,
    active SMALLINT DEFAULT 1
);

-- Staff request languages
CREATE TABLE IF NOT EXISTS tbl_staff_request_languages (
    "ID" SERIAL PRIMARY KEY,
    "request_ID" INTEGER,
    language_name VARCHAR(100),
    level VARCHAR(100),
    active SMALLINT DEFAULT 1
);

-- Staff request profile survey logs
CREATE TABLE IF NOT EXISTS tbl_staff_request_profile_survey_logs (
    id SERIAL PRIMARY KEY,
    "request_ID" INTEGER,
    "employer_ID" INTEGER,
    action VARCHAR(100),
    created_at TIMESTAMP DEFAULT NOW()
);

-- Staff request resources
CREATE TABLE IF NOT EXISTS tbl_staff_request_resources (
    "ID" SERIAL PRIMARY KEY,
    "request_ID" INTEGER,
    resource_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Staff request supervised employers
CREATE TABLE IF NOT EXISTS tbl_staff_request_supervised_employers (
    "ID" SERIAL PRIMARY KEY,
    "request_ID" INTEGER,
    "employer_ID" INTEGER,
    active SMALLINT DEFAULT 1
);

-- Staff request working hours
CREATE TABLE IF NOT EXISTS tbl_staff_request_working_hours (
    "ID" SERIAL PRIMARY KEY,
    "request_ID" INTEGER,
    schedule_description TEXT,
    active SMALLINT DEFAULT 1
);

-- Staff request canceled
CREATE TABLE IF NOT EXISTS tbl_staff_request_canceled (
    "ID" SERIAL PRIMARY KEY,
    "request_ID" INTEGER,
    reason TEXT,
    "employer_ID" INTEGER,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Type authorities
CREATE TABLE IF NOT EXISTS tbl_type_authorities (
    "ID" SERIAL PRIMARY KEY,
    type_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Screening
CREATE TABLE IF NOT EXISTS tbl_screening (
    "ID" SERIAL PRIMARY KEY,
    "process_ID" INTEGER,
    "seeker_ID" INTEGER,
    notes TEXT,
    status VARCHAR(50),
    created_at TIMESTAMP DEFAULT NOW()
);

-- Job charges competences
CREATE TABLE IF NOT EXISTS tbl_job_charges_job_competences (
    "ID" SERIAL PRIMARY KEY,
    "charge_ID" INTEGER,
    "competence_ID" INTEGER,
    active SMALLINT DEFAULT 1
);

-- Employee categories
CREATE TABLE IF NOT EXISTS tbl_ca_employee_categories (
    "ID" SERIAL PRIMARY KEY,
    category_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Employee types
CREATE TABLE IF NOT EXISTS tbl_employee_types (
    "ID" SERIAL PRIMARY KEY,
    type_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Nationalities (CA)
CREATE TABLE IF NOT EXISTS tbl_ca_nationalities (
    "ID" SERIAL PRIMARY KEY,
    nationality_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- CA countries
CREATE TABLE IF NOT EXISTS tbl_ca_countries (
    "ID" SERIAL PRIMARY KEY,
    country_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Insert initial admin
INSERT INTO tbl_admin (admin_username, admin_password, admin_name, admin_email)
VALUES ('admin@overall.pe', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador', 'admin@overall.pe')
ON CONFLICT DO NOTHING;

-- Insert initial ad codes row (ID=1)
INSERT INTO tbl_ad_codes ("ID") VALUES (1) ON CONFLICT DO NOTHING;

-- Insert default app config
INSERT INTO tbl_app_config ("key", value) VALUES 
('site_name', 'Portal de Empleo'),
('site_email', 'admin@overall.pe'),
('employer_signup_token', 'token123'),
('jobseeker_per_page', '10'),
('job_per_page', '10'),
('google_recaptcha_site_key', ''),
('google_recaptcha_secret_key', ''),
('google_analytics', ''),
('facebook_pixel', '')
ON CONFLICT ("key") DO NOTHING;

-- Insert default countries
INSERT INTO tbl_countries ("ID", country_name, iso_3166_1_alpha2, iso_3166_1_alpha3, phone_code, has_operation_overall) VALUES
(1, 'Afghanistan', 'AF', 'AFG', '+93', 0),
(2, 'Albania', 'AL', 'ALB', '+355', 0),
(3, 'Algeria', 'DZ', 'DZA', '+213', 0),
(4, 'Angola', 'AO', 'AGO', '+244', 0),
(5, 'Argentina', 'AR', 'ARG', '+54', 0),
(6, 'Australia', 'AU', 'AUS', '+61', 0),
(7, 'Austria', 'AT', 'AUT', '+43', 0),
(8, 'Belgium', 'BE', 'BEL', '+32', 0),
(9, 'Bolivia', 'BO', 'BOL', '+591', 0),
(10, 'Brazil', 'BR', 'BRA', '+55', 0),
(11, 'Canada', 'CA', 'CAN', '+1', 0),
(12, 'Chile', 'CL', 'CHL', '+56', 0),
(13, 'Colombia', 'CO', 'COL', '+57', 1),
(14, 'Costa Rica', 'CR', 'CRI', '+506', 0),
(15, 'Cuba', 'CU', 'CUB', '+53', 0),
(16, 'Dominican Republic', 'DO', 'DOM', '+1-809', 0),
(17, 'Ecuador', 'EC', 'ECU', '+593', 1),
(18, 'Egypt', 'EG', 'EGY', '+20', 0),
(19, 'El Salvador', 'SV', 'SLV', '+503', 0),
(20, 'France', 'FR', 'FRA', '+33', 0),
(21, 'Germany', 'DE', 'DEU', '+49', 0),
(22, 'Guatemala', 'GT', 'GTM', '+502', 0),
(23, 'Honduras', 'HN', 'HND', '+504', 0),
(24, 'India', 'IN', 'IND', '+91', 0),
(25, 'Indonesia', 'ID', 'IDN', '+62', 0),
(26, 'Iraq', 'IQ', 'IRQ', '+964', 0),
(27, 'Italy', 'IT', 'ITA', '+39', 0),
(28, 'Japan', 'JP', 'JPN', '+81', 0),
(29, 'Jordan', 'JO', 'JOR', '+962', 0),
(30, 'Kuwait', 'KW', 'KWT', '+965', 0),
(31, 'Lebanon', 'LB', 'LBN', '+961', 0),
(32, 'Libya', 'LY', 'LBY', '+218', 0),
(33, 'Malaysia', 'MY', 'MYS', '+60', 0),
(34, 'Mexico', 'MX', 'MEX', '+52', 1),
(35, 'Morocco', 'MA', 'MAR', '+212', 0),
(36, 'Netherlands', 'NL', 'NLD', '+31', 0),
(37, 'New Zealand', 'NZ', 'NZL', '+64', 0),
(38, 'Nicaragua', 'NI', 'NIC', '+505', 0),
(39, 'Nigeria', 'NG', 'NGA', '+234', 0),
(40, 'Norway', 'NO', 'NOR', '+47', 0),
(41, 'Pakistan', 'PK', 'PAK', '+92', 0),
(42, 'Panama', 'PA', 'PAN', '+507', 0),
(43, 'Paraguay', 'PY', 'PRY', '+595', 0),
(44, 'Philippines', 'PH', 'PHL', '+63', 0),
(45, 'Poland', 'PL', 'POL', '+48', 0),
(46, 'Portugal', 'PT', 'PRT', '+351', 0),
(47, 'Qatar', 'QA', 'QAT', '+974', 0),
(48, 'Romania', 'RO', 'ROU', '+40', 0),
(49, 'Russia', 'RU', 'RUS', '+7', 0),
(50, 'Saudi Arabia', 'SA', 'SAU', '+966', 0),
(51, 'Singapore', 'SG', 'SGP', '+65', 0),
(52, 'South Africa', 'ZA', 'ZAF', '+27', 0),
(53, 'South Korea', 'KR', 'KOR', '+82', 0),
(54, 'Spain', 'ES', 'ESP', '+34', 0),
(55, 'Sweden', 'SE', 'SWE', '+46', 0),
(56, 'Peru', 'PE', 'PER', '+51', 1),
(57, 'Switzerland', 'CH', 'CHE', '+41', 0),
(58, 'Thailand', 'TH', 'THA', '+66', 0),
(59, 'Turkey', 'TR', 'TUR', '+90', 0),
(60, 'United Arab Emirates', 'AE', 'ARE', '+971', 0),
(61, 'United Kingdom', 'GB', 'GBR', '+44', 0),
(62, 'United States', 'US', 'USA', '+1', 0),
(63, 'Uruguay', 'UY', 'URY', '+598', 0),
(64, 'Venezuela', 'VE', 'VEN', '+58', 0),
(65, 'Vietnam', 'VN', 'VNM', '+84', 0)
ON CONFLICT ("ID") DO NOTHING;

SELECT setval(pg_get_serial_sequence('tbl_countries', 'ID'), 100, true);

-- Default cities for Peru
INSERT INTO tbl_cities ("ID", city_name, country_id, sort_order, "show", is_popular) VALUES
(1, 'Lima', 56, 1, 1, 'yes'),
(2, 'Arequipa', 56, 2, 1, 'yes'),
(3, 'Trujillo', 56, 3, 1, 'yes'),
(4, 'Chiclayo', 56, 4, 1, 'no'),
(5, 'Piura', 56, 5, 1, 'no'),
(6, 'Iquitos', 56, 6, 1, 'no'),
(7, 'Cusco', 56, 7, 1, 'yes'),
(8, 'Huancayo', 56, 8, 1, 'no'),
(9, 'Tacna', 56, 9, 1, 'no'),
(10, 'Pucallpa', 56, 10, 1, 'no')
ON CONFLICT ("ID") DO NOTHING;

SELECT setval(pg_get_serial_sequence('tbl_cities', 'ID'), 20, true);

-- Default industries
INSERT INTO tbl_job_industries ("ID", industry_name) VALUES
(1, 'Tecnología'),
(2, 'Salud'),
(3, 'Educación'),
(4, 'Construcción'),
(5, 'Minería'),
(6, 'Finanzas y Banca'),
(7, 'Comercio y Retail'),
(8, 'Manufactura'),
(9, 'Servicios'),
(10, 'Logística y Transporte'),
(11, 'Agroindustria'),
(12, 'Energía'),
(13, 'Telecomunicaciones'),
(14, 'Gobierno y Sector Público'),
(15, 'Hotelería y Turismo')
ON CONFLICT ("ID") DO NOTHING;

SELECT setval(pg_get_serial_sequence('tbl_job_industries', 'ID'), 20, true);

-- Default qualifications
INSERT INTO tbl_qualifications ("ID", qualification_name) VALUES
(1, 'Secundaria completa'),
(2, 'Técnico'),
(3, 'Bachiller'),
(4, 'Licenciatura / Título profesional'),
(5, 'Maestría'),
(6, 'Doctorado')
ON CONFLICT ("ID") DO NOTHING;

SELECT setval(pg_get_serial_sequence('tbl_qualifications', 'ID'), 10, true);

-- Default civil status
INSERT INTO tbl_civil_status (id, name, country_id, active) VALUES
(1, 'Soltero(a)', 56, 1),
(2, 'Casado(a)', 56, 1),
(3, 'Conviviente', 56, 1),
(4, 'Divorciado(a)', 56, 1),
(5, 'Viudo(a)', 56, 1),
(1, 'Soltero(a)', 17, 1),
(2, 'Casado(a)', 17, 1),
(3, 'Divorciado(a)', 17, 1),
(4, 'Viudo(a)', 17, 1)
ON CONFLICT DO NOTHING;

SELECT setval(pg_get_serial_sequence('tbl_civil_status', 'id'), 20, true);

-- Default disabilities
INSERT INTO tbl_disabilities (id, name, active) VALUES
(1, 'Visual', 1),
(2, 'Auditiva', 1),
(3, 'Motriz', 1),
(4, 'Mental o Psicosocial', 1),
(5, 'Intelectual', 1),
(6, 'Otra', 1)
ON CONFLICT (id) DO NOTHING;

-- Default genders
INSERT INTO tbl_genders (id, name, active) VALUES
(1, 'Masculino', 1),
(2, 'Femenino', 1),
(3, 'No binario', 1),
(4, 'Prefiero no indicar', 1)
ON CONFLICT (id) DO NOTHING;

-- Default identity document types
INSERT INTO tbl_identity_document_types (id, name, country_id, active) VALUES
(1, 'DNI', 56, 1),
(2, 'Pasaporte', 56, 1),
(3, 'Carnet de Extranjería', 56, 1),
(4, 'Cédula de Identidad', 17, 1),
(5, 'Pasaporte', 17, 1),
(6, 'CURP', 34, 1),
(7, 'Pasaporte', 34, 1),
(8, 'Cédula de Ciudadanía', 13, 1),
(9, 'Pasaporte', 13, 1)
ON CONFLICT (id) DO NOTHING;

-- Default functional areas  
INSERT INTO tbl_job_functional_areas ("ID", functional_area_name) VALUES
(1, 'Administración'),
(2, 'Comercial / Ventas'),
(3, 'Contabilidad / Finanzas'),
(4, 'Ingeniería'),
(5, 'Producción / Operaciones'),
(6, 'Recursos Humanos'),
(7, 'Sistemas / TI'),
(8, 'Legal'),
(9, 'Marketing'),
(10, 'Logística / Cadena de suministro')
ON CONFLICT ("ID") DO NOTHING;

SELECT setval(pg_get_serial_sequence('tbl_job_functional_areas', 'ID'), 20, true);

-- Schema fixes for Replit migration (added missing columns)
ALTER TABLE tbl_logs ADD COLUMN IF NOT EXISTS session_data TEXT;
ALTER TABLE tbl_logs ADD COLUMN IF NOT EXISTS get_data TEXT;
ALTER TABLE tbl_logs ADD COLUMN IF NOT EXISTS post_data TEXT;

ALTER TABLE tbl_post_jobs RENAME COLUMN industry_id TO "industry_ID" -- already applied;
ALTER TABLE tbl_post_jobs ADD COLUMN IF NOT EXISTS city VARCHAR(255);
ALTER TABLE tbl_post_jobs ADD COLUMN IF NOT EXISTS dated TIMESTAMP DEFAULT NOW();
ALTER TABLE tbl_post_jobs ADD COLUMN IF NOT EXISTS last_date DATE;
ALTER TABLE tbl_post_jobs ADD COLUMN IF NOT EXISTS is_featured SMALLINT DEFAULT 0;
ALTER TABLE tbl_post_jobs ADD COLUMN IF NOT EXISTS sts VARCHAR(50) DEFAULT 'active';
ALTER TABLE tbl_post_jobs ADD COLUMN IF NOT EXISTS pay VARCHAR(255);
ALTER TABLE tbl_post_jobs ADD COLUMN IF NOT EXISTS "request_ID" INTEGER;
ALTER TABLE tbl_post_jobs ADD COLUMN IF NOT EXISTS job_mode VARCHAR(100);
ALTER TABLE tbl_post_jobs ADD COLUMN IF NOT EXISTS contact_person VARCHAR(255);
ALTER TABLE tbl_post_jobs ADD COLUMN IF NOT EXISTS contact_email VARCHAR(255);
ALTER TABLE tbl_post_jobs ADD COLUMN IF NOT EXISTS applications_count INTEGER DEFAULT 0;
ALTER TABLE tbl_post_jobs ADD COLUMN IF NOT EXISTS viewer_count INTEGER DEFAULT 0;
ALTER TABLE tbl_post_jobs ADD COLUMN IF NOT EXISTS job_ignore SMALLINT DEFAULT 0;
ALTER TABLE tbl_post_jobs ADD COLUMN IF NOT EXISTS required_skills TEXT;

ALTER TABLE tbl_companies ADD COLUMN IF NOT EXISTS sts VARCHAR(50) DEFAULT 'active';
ALTER TABLE tbl_companies ADD COLUMN IF NOT EXISTS old_company_id INTEGER;
ALTER TABLE tbl_companies ADD COLUMN IF NOT EXISTS company_email VARCHAR(255);
ALTER TABLE tbl_companies ADD COLUMN IF NOT EXISTS company_fax VARCHAR(100);
ALTER TABLE tbl_companies ADD COLUMN IF NOT EXISTS company_folder VARCHAR(255);
ALTER TABLE tbl_companies ADD COLUMN IF NOT EXISTS company_type VARCHAR(100);
ALTER TABLE tbl_companies ADD COLUMN IF NOT EXISTS client_code VARCHAR(100);
ALTER TABLE tbl_companies ADD COLUMN IF NOT EXISTS cod_business_unit VARCHAR(100);
ALTER TABLE tbl_companies ADD COLUMN IF NOT EXISTS cod_clie VARCHAR(100);
ALTER TABLE tbl_companies ADD COLUMN IF NOT EXISTS company_ceo VARCHAR(255);
ALTER TABLE tbl_companies ADD COLUMN IF NOT EXISTS established_in VARCHAR(10);
ALTER TABLE tbl_companies ADD COLUMN IF NOT EXISTS no_cia VARCHAR(50);
ALTER TABLE tbl_companies ADD COLUMN IF NOT EXISTS no_of_offices INTEGER DEFAULT 0;
ALTER TABLE tbl_companies ADD COLUMN IF NOT EXISTS system_internal SMALLINT DEFAULT 0;
ALTER TABLE tbl_companies ADD COLUMN IF NOT EXISTS "recruiter_ID" INTEGER;
ALTER TABLE tbl_companies ADD COLUMN IF NOT EXISTS city_id INTEGER;

ALTER TABLE tbl_employers ADD COLUMN IF NOT EXISTS first_name VARCHAR(255);
ALTER TABLE tbl_employers ADD COLUMN IF NOT EXISTS last_name VARCHAR(255);
ALTER TABLE tbl_employers ADD COLUMN IF NOT EXISTS country VARCHAR(255);
ALTER TABLE tbl_employers ADD COLUMN IF NOT EXISTS sts VARCHAR(50) DEFAULT 'active';
ALTER TABLE tbl_employers ADD COLUMN IF NOT EXISTS top_employer SMALLINT DEFAULT 0;
ALTER TABLE tbl_employers ADD COLUMN IF NOT EXISTS "employer_ID" INTEGER;

ALTER TABLE tbl_job_industries ADD COLUMN IF NOT EXISTS top_category VARCHAR(10) DEFAULT 'no';
ALTER TABLE tbl_job_industries ADD COLUMN IF NOT EXISTS industry_icon VARCHAR(255);
ALTER TABLE tbl_job_industries ADD COLUMN IF NOT EXISTS no_of_jobs INTEGER DEFAULT 0;
