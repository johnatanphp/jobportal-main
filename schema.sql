-- PostgreSQL Schema for Portal de Empleo (Job Portal)

-- App config table
CREATE TABLE IF NOT EXISTS tbl_app_config (
    id SERIAL PRIMARY KEY,
    "key" VARCHAR(255) NOT NULL UNIQUE,
    value TEXT
);

-- Admin table
CREATE TABLE IF NOT EXISTS tbl_admin (
    "ID" SERIAL PRIMARY KEY,
    admin_username VARCHAR(255),
    admin_password VARCHAR(255),
    admin_name VARCHAR(255),
    admin_email VARCHAR(255),
    profile_photo VARCHAR(255),
    created_at TIMESTAMP DEFAULT NOW()
);

-- Ad codes table
CREATE TABLE IF NOT EXISTS tbl_ad_codes (
    "ID" SERIAL PRIMARY KEY,
    ad_title VARCHAR(255),
    ad_code TEXT,
    ad_position VARCHAR(100),
    sts SMALLINT DEFAULT 1
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
    active SMALLINT DEFAULT 1
);

-- Job industries table
CREATE TABLE IF NOT EXISTS tbl_job_industries (
    "ID" SERIAL PRIMARY KEY,
    industry_name VARCHAR(255),
    top_category SMALLINT DEFAULT 0,
    active SMALLINT DEFAULT 1
);

-- Genders table
CREATE TABLE IF NOT EXISTS tbl_genders (
    "ID" SERIAL PRIMARY KEY,
    gender_name VARCHAR(100),
    active SMALLINT DEFAULT 1
);

-- Civil status table
CREATE TABLE IF NOT EXISTS tbl_civil_status (
    "ID" SERIAL PRIMARY KEY,
    civil_status_name VARCHAR(100),
    active SMALLINT DEFAULT 1
);

-- Identity document types
CREATE TABLE IF NOT EXISTS tbl_identity_document_types (
    "ID" SERIAL PRIMARY KEY,
    document_type_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Skills table
CREATE TABLE IF NOT EXISTS tbl_skills (
    "ID" SERIAL PRIMARY KEY,
    skill_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Job functional areas
CREATE TABLE IF NOT EXISTS tbl_job_functional_areas (
    "ID" SERIAL PRIMARY KEY,
    area_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Companies table
CREATE TABLE IF NOT EXISTS tbl_companies (
    "ID" SERIAL PRIMARY KEY,
    company_name VARCHAR(255),
    company_slug VARCHAR(255),
    company_logo VARCHAR(255),
    company_description TEXT,
    company_website VARCHAR(255),
    company_email VARCHAR(255),
    company_phone VARCHAR(100),
    company_address TEXT,
    city INTEGER,
    country_id INTEGER,
    industry_id INTEGER,
    sts VARCHAR(20) DEFAULT 'active',
    created_at TIMESTAMP DEFAULT NOW()
);

-- Employers table
CREATE TABLE IF NOT EXISTS tbl_employers (
    "ID" SERIAL PRIMARY KEY,
    first_name VARCHAR(255),
    last_name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    pass_code VARCHAR(255),
    company_ID INTEGER,
    profile_photo VARCHAR(255),
    phone VARCHAR(100),
    sts VARCHAR(20) DEFAULT 'active',
    email_verified SMALLINT DEFAULT 0,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Job seekers table
CREATE TABLE IF NOT EXISTS tbl_job_seekers (
    "ID" SERIAL PRIMARY KEY,
    first_name VARCHAR(255),
    last_name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    phone VARCHAR(100),
    gender_id INTEGER,
    civil_status_id INTEGER,
    dob DATE,
    profile_photo VARCHAR(255),
    resume_file VARCHAR(255),
    city INTEGER,
    country_id INTEGER,
    sts VARCHAR(20) DEFAULT 'active',
    email_verified SMALLINT DEFAULT 0,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Posted jobs table
CREATE TABLE IF NOT EXISTS tbl_post_jobs (
    "ID" SERIAL PRIMARY KEY,
    job_title VARCHAR(255),
    job_slug VARCHAR(255),
    job_description TEXT,
    employer_ID INTEGER,
    company_ID INTEGER,
    industry_ID INTEGER,
    city INTEGER,
    country_id INTEGER,
    min_salary DECIMAL(10,2),
    max_salary DECIMAL(10,2),
    dated TIMESTAMP DEFAULT NOW(),
    last_date DATE,
    is_featured SMALLINT DEFAULT 0,
    sts VARCHAR(20) DEFAULT 'active',
    job_type VARCHAR(50),
    experience_required VARCHAR(100),
    education_required VARCHAR(255),
    created_at TIMESTAMP DEFAULT NOW()
);

-- Applied jobs (seeker applied for job)
CREATE TABLE IF NOT EXISTS tbl_seeker_applied_for_job (
    "ID" SERIAL PRIMARY KEY,
    job_id INTEGER,
    seeker_id INTEGER,
    employer_id INTEGER,
    company_id INTEGER,
    applied_date TIMESTAMP DEFAULT NOW(),
    status VARCHAR(50) DEFAULT 'pending',
    cover_letter TEXT
);

-- Seeker academic records
CREATE TABLE IF NOT EXISTS tbl_seeker_academic (
    "ID" SERIAL PRIMARY KEY,
    seeker_id INTEGER,
    institution_name VARCHAR(255),
    degree VARCHAR(255),
    field_of_study VARCHAR(255),
    start_date DATE,
    end_date DATE,
    is_current SMALLINT DEFAULT 0
);

-- Seeker experience
CREATE TABLE IF NOT EXISTS tbl_seeker_experience (
    "ID" SERIAL PRIMARY KEY,
    seeker_id INTEGER,
    company_name VARCHAR(255),
    job_title VARCHAR(255),
    start_date DATE,
    end_date DATE,
    is_current SMALLINT DEFAULT 0,
    description TEXT
);

-- Seeker skills
CREATE TABLE IF NOT EXISTS tbl_seeker_skills (
    "ID" SERIAL PRIMARY KEY,
    seeker_id INTEGER,
    skill_id INTEGER,
    level VARCHAR(50)
);

-- Logs table
CREATE TABLE IF NOT EXISTS tbl_logs (
    "ID" SERIAL PRIMARY KEY,
    type VARCHAR(100),
    message TEXT,
    session_data TEXT,
    get_data TEXT,
    post_data TEXT,
    server_data TEXT,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Banks
CREATE TABLE IF NOT EXISTS tbl_banks (
    "ID" SERIAL PRIMARY KEY,
    bank_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Business units
CREATE TABLE IF NOT EXISTS tbl_business_units (
    "ID" SERIAL PRIMARY KEY,
    unit_name VARCHAR(255),
    company_id INTEGER,
    active SMALLINT DEFAULT 1
);

-- Internal areas
CREATE TABLE IF NOT EXISTS tbl_internal_areas (
    "ID" SERIAL PRIMARY KEY,
    area_name VARCHAR(255),
    company_id INTEGER,
    active SMALLINT DEFAULT 1
);

-- Job charges
CREATE TABLE IF NOT EXISTS tbl_job_charges (
    "ID" SERIAL PRIMARY KEY,
    charge_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Disabilities
CREATE TABLE IF NOT EXISTS tbl_disabilities (
    "ID" SERIAL PRIMARY KEY,
    disability_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Qualifications
CREATE TABLE IF NOT EXISTS tbl_qualifications (
    "ID" SERIAL PRIMARY KEY,
    qualification_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Institutes
CREATE TABLE IF NOT EXISTS tbl_institute (
    "ID" SERIAL PRIMARY KEY,
    institute_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Careers
CREATE TABLE IF NOT EXISTS tbl_careers (
    "ID" SERIAL PRIMARY KEY,
    career_name VARCHAR(255),
    institute_id INTEGER,
    active SMALLINT DEFAULT 1
);

-- Job questions
CREATE TABLE IF NOT EXISTS tbl_job_questions (
    "ID" SERIAL PRIMARY KEY,
    job_id INTEGER,
    question_text TEXT,
    question_type VARCHAR(50),
    is_required SMALLINT DEFAULT 0
);

-- Seeker applied job answer values
CREATE TABLE IF NOT EXISTS tbl_seeker_applied_job_answers_values (
    "ID" SERIAL PRIMARY KEY,
    applied_job_id INTEGER,
    question_id INTEGER,
    answer_value TEXT
);

-- CMS pages
CREATE TABLE IF NOT EXISTS tbl_cms (
    "ID" SERIAL PRIMARY KEY,
    page_title VARCHAR(255),
    page_slug VARCHAR(255),
    page_content TEXT,
    sts SMALLINT DEFAULT 1
);

-- Newsletters
CREATE TABLE IF NOT EXISTS tbl_newsletters (
    "ID" SERIAL PRIMARY KEY,
    email VARCHAR(255),
    subscribed_at TIMESTAMP DEFAULT NOW()
);

-- Success stories
CREATE TABLE IF NOT EXISTS tbl_success_stories (
    "ID" SERIAL PRIMARY KEY,
    title VARCHAR(255),
    content TEXT,
    image VARCHAR(255),
    sts SMALLINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Company config
CREATE TABLE IF NOT EXISTS tbl_company_config (
    "ID" SERIAL PRIMARY KEY,
    company_id INTEGER,
    config_key VARCHAR(255),
    config_value TEXT
);

-- Employer profiles
CREATE TABLE IF NOT EXISTS tbl_employer_profiles (
    "ID" SERIAL PRIMARY KEY,
    employer_id INTEGER,
    profile_type VARCHAR(100),
    sts SMALLINT DEFAULT 1
);

-- Employer RRHH types
CREATE TABLE IF NOT EXISTS tbl_employer_rrhh_types (
    "ID" SERIAL PRIMARY KEY,
    rrhh_type_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Cost center manager
CREATE TABLE IF NOT EXISTS tbl_cost_center_manager (
    "ID" SERIAL PRIMARY KEY,
    cost_center_name VARCHAR(255),
    company_id INTEGER,
    active SMALLINT DEFAULT 1
);

-- Screening
CREATE TABLE IF NOT EXISTS tbl_screening (
    "ID" SERIAL PRIMARY KEY,
    job_id INTEGER,
    seeker_id INTEGER,
    status VARCHAR(50),
    created_at TIMESTAMP DEFAULT NOW()
);

-- Screening types
CREATE TABLE IF NOT EXISTS tbl_screening_types (
    "ID" SERIAL PRIMARY KEY,
    type_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Screening batch
CREATE TABLE IF NOT EXISTS tbl_screening_batch (
    "ID" SERIAL PRIMARY KEY,
    job_id INTEGER,
    batch_name VARCHAR(255),
    created_at TIMESTAMP DEFAULT NOW()
);

-- Screening batch items
CREATE TABLE IF NOT EXISTS tbl_screening_batch_items (
    "ID" SERIAL PRIMARY KEY,
    batch_id INTEGER,
    seeker_id INTEGER,
    status VARCHAR(50)
);

-- Staff requests
CREATE TABLE IF NOT EXISTS tbl_staff_requests (
    "ID" SERIAL PRIMARY KEY,
    company_id INTEGER,
    requested_by INTEGER,
    job_title VARCHAR(255),
    quantity INTEGER,
    status VARCHAR(50),
    created_at TIMESTAMP DEFAULT NOW()
);

-- Staff request assignments
CREATE TABLE IF NOT EXISTS tbl_staff_request_assignments (
    "ID" SERIAL PRIMARY KEY,
    staff_request_id INTEGER,
    employer_id INTEGER,
    assigned_at TIMESTAMP DEFAULT NOW()
);

-- Staff request authorities
CREATE TABLE IF NOT EXISTS tbl_staff_request_authorities (
    "ID" SERIAL PRIMARY KEY,
    staff_request_id INTEGER,
    employer_id INTEGER,
    authority_level VARCHAR(50)
);

-- Staff request authorizations
CREATE TABLE IF NOT EXISTS tbl_staff_request_authorizations (
    "ID" SERIAL PRIMARY KEY,
    staff_request_id INTEGER,
    authorized_by INTEGER,
    authorized_at TIMESTAMP DEFAULT NOW(),
    status VARCHAR(50)
);

-- Staff request assigned employers
CREATE TABLE IF NOT EXISTS tbl_staff_request_assigned_employers (
    "ID" SERIAL PRIMARY KEY,
    staff_request_id INTEGER,
    employer_id INTEGER
);

-- Staff request supervised employers
CREATE TABLE IF NOT EXISTS tbl_staff_request_supervised_employers (
    "ID" SERIAL PRIMARY KEY,
    staff_request_id INTEGER,
    employer_id INTEGER
);

-- Staff request job functions
CREATE TABLE IF NOT EXISTS tbl_staff_request_job_functions (
    "ID" SERIAL PRIMARY KEY,
    staff_request_id INTEGER,
    function_description TEXT
);

-- Staff request languages
CREATE TABLE IF NOT EXISTS tbl_staff_request_languages (
    "ID" SERIAL PRIMARY KEY,
    staff_request_id INTEGER,
    language_name VARCHAR(100),
    proficiency_level VARCHAR(50)
);

-- Staff request resources
CREATE TABLE IF NOT EXISTS tbl_staff_request_resources (
    "ID" SERIAL PRIMARY KEY,
    staff_request_id INTEGER,
    resource_name VARCHAR(255)
);

-- Staff request computing
CREATE TABLE IF NOT EXISTS tbl_staff_request_computing (
    "ID" SERIAL PRIMARY KEY,
    staff_request_id INTEGER,
    software_name VARCHAR(255),
    proficiency_level VARCHAR(50)
);

-- Staff request working hours
CREATE TABLE IF NOT EXISTS tbl_staff_request_working_hours (
    "ID" SERIAL PRIMARY KEY,
    staff_request_id INTEGER,
    hours_per_week INTEGER,
    schedule TEXT
);

-- Staff request additional benefits
CREATE TABLE IF NOT EXISTS tbl_staff_request_additional_benefits (
    "ID" SERIAL PRIMARY KEY,
    staff_request_id INTEGER,
    benefit_description TEXT
);

-- Staff request additional competences
CREATE TABLE IF NOT EXISTS tbl_staff_request_additional_competences (
    "ID" SERIAL PRIMARY KEY,
    staff_request_id INTEGER,
    competence_description TEXT
);

-- Staff request fixed competences
CREATE TABLE IF NOT EXISTS tbl_staff_request_fixed_competences (
    "ID" SERIAL PRIMARY KEY,
    staff_request_id INTEGER,
    competence_id INTEGER
);

-- Staff request gantt
CREATE TABLE IF NOT EXISTS tbl_staff_request_gantt (
    "ID" SERIAL PRIMARY KEY,
    staff_request_id INTEGER,
    gantt_type_id INTEGER
);

-- Staff request gantt activities
CREATE TABLE IF NOT EXISTS tbl_staff_request_gantt_activities (
    "ID" SERIAL PRIMARY KEY,
    gantt_id INTEGER,
    activity_name VARCHAR(255)
);

-- Gantt types
CREATE TABLE IF NOT EXISTS tbl_gantt_types (
    "ID" SERIAL PRIMARY KEY,
    type_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Gantt activities
CREATE TABLE IF NOT EXISTS tbl_gantt_activities (
    "ID" SERIAL PRIMARY KEY,
    gantt_type_id INTEGER,
    activity_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Factor types
CREATE TABLE IF NOT EXISTS tbl_factor_types (
    "ID" SERIAL PRIMARY KEY,
    factor_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Job layout tables
CREATE TABLE IF NOT EXISTS tbl_job_layouts (
    "ID" SERIAL PRIMARY KEY,
    job_id INTEGER,
    layout_data TEXT,
    created_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS tbl_job_layout_skills (
    "ID" SERIAL PRIMARY KEY,
    job_layout_id INTEGER,
    skill_id INTEGER
);

CREATE TABLE IF NOT EXISTS tbl_job_layout_resources (
    "ID" SERIAL PRIMARY KEY,
    job_layout_id INTEGER,
    resource_name VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS tbl_job_layout_responsibilities (
    "ID" SERIAL PRIMARY KEY,
    job_layout_id INTEGER,
    responsibility_text TEXT
);

CREATE TABLE IF NOT EXISTS tbl_job_layout_laboral_benefits (
    "ID" SERIAL PRIMARY KEY,
    job_layout_id INTEGER,
    benefit_description TEXT
);

CREATE TABLE IF NOT EXISTS tbl_job_layout_factor_valuations (
    "ID" SERIAL PRIMARY KEY,
    job_layout_id INTEGER,
    factor_type_id INTEGER,
    valuation_score INTEGER
);

CREATE TABLE IF NOT EXISTS tbl_job_layout_codes (
    "ID" SERIAL PRIMARY KEY,
    job_layout_id INTEGER,
    code VARCHAR(100)
);

CREATE TABLE IF NOT EXISTS tbl_job_layout_code_integrations (
    "ID" SERIAL PRIMARY KEY,
    job_layout_code_id INTEGER,
    integration_data TEXT
);

CREATE TABLE IF NOT EXISTS tbl_job_layout_disability_sections (
    "ID" SERIAL PRIMARY KEY,
    section_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

CREATE TABLE IF NOT EXISTS tbl_job_layout_disability_themes (
    "ID" SERIAL PRIMARY KEY,
    section_id INTEGER,
    theme_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

CREATE TABLE IF NOT EXISTS tbl_job_layout_disability_subthemes (
    "ID" SERIAL PRIMARY KEY,
    theme_id INTEGER,
    subtheme_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

CREATE TABLE IF NOT EXISTS tbl_job_layout_disability_options (
    "ID" SERIAL PRIMARY KEY,
    subtheme_id INTEGER,
    option_text VARCHAR(255),
    active SMALLINT DEFAULT 1
);

CREATE TABLE IF NOT EXISTS tbl_job_layout_disability_eligibles (
    "ID" SERIAL PRIMARY KEY,
    job_layout_id INTEGER,
    disability_option_id INTEGER
);

-- Job profile tables
CREATE TABLE IF NOT EXISTS tbl_job_profile_codes (
    "ID" SERIAL PRIMARY KEY,
    code VARCHAR(100),
    description TEXT
);

CREATE TABLE IF NOT EXISTS tbl_job_profile_disability (
    "ID" SERIAL PRIMARY KEY,
    job_id INTEGER,
    disability_id INTEGER
);

CREATE TABLE IF NOT EXISTS tbl_job_profile_disability_eligibles (
    "ID" SERIAL PRIMARY KEY,
    job_id INTEGER,
    disability_option_id INTEGER
);

-- Job charge skills
CREATE TABLE IF NOT EXISTS tbl_job_charge_skills (
    "ID" SERIAL PRIMARY KEY,
    job_charge_id INTEGER,
    skill_id INTEGER
);

-- Recruitment tables
CREATE TABLE IF NOT EXISTS tbl_recruitment_stages (
    "ID" SERIAL PRIMARY KEY,
    stage_name VARCHAR(255),
    stage_order INTEGER,
    active SMALLINT DEFAULT 1
);

CREATE TABLE IF NOT EXISTS tbl_recruitment_processes (
    "ID" SERIAL PRIMARY KEY,
    job_id INTEGER,
    stage_id INTEGER,
    status VARCHAR(50),
    created_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS tbl_recruitment_candidates (
    "ID" SERIAL PRIMARY KEY,
    recruitment_process_id INTEGER,
    seeker_id INTEGER,
    status VARCHAR(50),
    created_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS tbl_recruitment_short_list_candidates (
    "ID" SERIAL PRIMARY KEY,
    job_id INTEGER,
    seeker_id INTEGER,
    status VARCHAR(50),
    created_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS tbl_recruitment_tray_candidates (
    "ID" SERIAL PRIMARY KEY,
    job_id INTEGER,
    seeker_id INTEGER,
    status VARCHAR(50),
    created_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS tbl_recruitment_tray_status (
    "ID" SERIAL PRIMARY KEY,
    status_name VARCHAR(100),
    active SMALLINT DEFAULT 1
);

CREATE TABLE IF NOT EXISTS tbl_recruitment_tray_screening_batches (
    "ID" SERIAL PRIMARY KEY,
    tray_candidate_id INTEGER,
    batch_id INTEGER
);

CREATE TABLE IF NOT EXISTS tbl_recruitment_rrhh_groups (
    "ID" SERIAL PRIMARY KEY,
    group_name VARCHAR(255),
    company_id INTEGER,
    active SMALLINT DEFAULT 1
);

CREATE TABLE IF NOT EXISTS tbl_recruitment_rrhh_group_users (
    "ID" SERIAL PRIMARY KEY,
    group_id INTEGER,
    employer_id INTEGER
);

CREATE TABLE IF NOT EXISTS tbl_recruitment_rrhh_group_assignments (
    "ID" SERIAL PRIMARY KEY,
    group_id INTEGER,
    job_id INTEGER
);

CREATE TABLE IF NOT EXISTS tbl_recruitment_attached_documents (
    "ID" SERIAL PRIMARY KEY,
    recruitment_id INTEGER,
    document_name VARCHAR(255),
    document_path VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS tbl_recruitment_document_types (
    "ID" SERIAL PRIMARY KEY,
    type_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

CREATE TABLE IF NOT EXISTS tbl_recruitment_document_requests (
    "ID" SERIAL PRIMARY KEY,
    recruitment_id INTEGER,
    document_type_id INTEGER,
    status VARCHAR(50)
);

CREATE TABLE IF NOT EXISTS tbl_recruitment_seeker_documents (
    "ID" SERIAL PRIMARY KEY,
    recruitment_id INTEGER,
    seeker_id INTEGER,
    document_type_id INTEGER,
    document_path VARCHAR(255),
    status VARCHAR(50)
);

CREATE TABLE IF NOT EXISTS tbl_recruitment_seeker_other_documents (
    "ID" SERIAL PRIMARY KEY,
    recruitment_id INTEGER,
    seeker_id INTEGER,
    document_name VARCHAR(255),
    document_path VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS tbl_recruitment_contracts (
    "ID" SERIAL PRIMARY KEY,
    recruitment_id INTEGER,
    contract_type_id INTEGER,
    status VARCHAR(50),
    created_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS tbl_recruitment_contract_document_types (
    "ID" SERIAL PRIMARY KEY,
    type_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

CREATE TABLE IF NOT EXISTS tbl_recruitment_contract_documents (
    "ID" SERIAL PRIMARY KEY,
    contract_id INTEGER,
    document_type_id INTEGER,
    document_path VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS tbl_recruitment_process_contracts (
    "ID" SERIAL PRIMARY KEY,
    process_id INTEGER,
    contract_id INTEGER
);

CREATE TABLE IF NOT EXISTS tbl_recruitment_process_documents (
    "ID" SERIAL PRIMARY KEY,
    process_id INTEGER,
    document_path VARCHAR(255),
    document_type VARCHAR(100)
);

CREATE TABLE IF NOT EXISTS tbl_recruitment_process_document_stages (
    "ID" SERIAL PRIMARY KEY,
    process_document_id INTEGER,
    stage_id INTEGER,
    status VARCHAR(50)
);

CREATE TABLE IF NOT EXISTS tbl_recruitment_period_rules (
    "ID" SERIAL PRIMARY KEY,
    rule_name VARCHAR(255),
    days INTEGER
);

CREATE TABLE IF NOT EXISTS tbl_recruitment_contracts_synchronization_logs (
    "ID" SERIAL PRIMARY KEY,
    contract_id INTEGER,
    log_message TEXT,
    created_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS tbl_recruitment_candidate_fits_tmp (
    "ID" SERIAL PRIMARY KEY,
    candidate_id INTEGER,
    fit_score DECIMAL(5,2),
    created_at TIMESTAMP DEFAULT NOW()
);

-- Exam request tables
CREATE TABLE IF NOT EXISTS tbl_exam_requests (
    "ID" SERIAL PRIMARY KEY,
    seeker_id INTEGER,
    exam_type_id INTEGER,
    status VARCHAR(50),
    created_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS tbl_exam_request_types (
    "ID" SERIAL PRIMARY KEY,
    type_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

CREATE TABLE IF NOT EXISTS tbl_exam_request_results (
    "ID" SERIAL PRIMARY KEY,
    exam_request_id INTEGER,
    result_type_id INTEGER,
    result_value TEXT
);

CREATE TABLE IF NOT EXISTS tbl_exam_request_result_types (
    "ID" SERIAL PRIMARY KEY,
    type_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

CREATE TABLE IF NOT EXISTS tbl_exam_request_schedules (
    "ID" SERIAL PRIMARY KEY,
    exam_request_id INTEGER,
    scheduled_at TIMESTAMP,
    location VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS tbl_exam_request_seekers (
    "ID" SERIAL PRIMARY KEY,
    exam_request_id INTEGER,
    seeker_id INTEGER,
    status VARCHAR(50)
);

CREATE TABLE IF NOT EXISTS tbl_exam_request_status (
    "ID" SERIAL PRIMARY KEY,
    status_name VARCHAR(100),
    active SMALLINT DEFAULT 1
);

CREATE TABLE IF NOT EXISTS tbl_exam_emo_types (
    "ID" SERIAL PRIMARY KEY,
    type_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

CREATE TABLE IF NOT EXISTS tbl_exam_request_overall_emails (
    "ID" SERIAL PRIMARY KEY,
    exam_request_id INTEGER,
    email_sent_to VARCHAR(255),
    sent_at TIMESTAMP DEFAULT NOW()
);

-- Seeker document photos
CREATE TABLE IF NOT EXISTS tbl_seeker_document_photos (
    "ID" SERIAL PRIMARY KEY,
    seeker_id INTEGER,
    document_type_id INTEGER,
    photo_path VARCHAR(255),
    uploaded_at TIMESTAMP DEFAULT NOW()
);

-- Seeker identification documents
CREATE TABLE IF NOT EXISTS tbl_seeker_identification_documents (
    "ID" SERIAL PRIMARY KEY,
    seeker_id INTEGER,
    document_type_id INTEGER,
    document_number VARCHAR(100),
    issued_date DATE,
    expiry_date DATE
);

-- Seeker immigration records
CREATE TABLE IF NOT EXISTS tbl_seeker_immigration_records (
    "ID" SERIAL PRIMARY KEY,
    seeker_id INTEGER,
    record_type VARCHAR(100),
    record_data TEXT,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Seeker entries
CREATE TABLE IF NOT EXISTS tbl_seeker_entries (
    "ID" SERIAL PRIMARY KEY,
    seeker_id INTEGER,
    company_id INTEGER,
    entry_date TIMESTAMP DEFAULT NOW(),
    status VARCHAR(50)
);

CREATE TABLE IF NOT EXISTS tbl_seeker_entries_channels (
    "ID" SERIAL PRIMARY KEY,
    entry_id INTEGER,
    channel_name VARCHAR(100)
);

CREATE TABLE IF NOT EXISTS tbl_seeker_entries_clients (
    "ID" SERIAL PRIMARY KEY,
    entry_id INTEGER,
    client_company_id INTEGER
);

CREATE TABLE IF NOT EXISTS tbl_seeker_entries_jobs (
    "ID" SERIAL PRIMARY KEY,
    entry_id INTEGER,
    job_id INTEGER
);

-- Seeker domicile affidavit
CREATE TABLE IF NOT EXISTS tbl_seeker_domicile_affidavits (
    "ID" SERIAL PRIMARY KEY,
    seeker_id INTEGER,
    affidavit_data TEXT,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Seeker police records
CREATE TABLE IF NOT EXISTS tbl_seeker_police_records (
    "ID" SERIAL PRIMARY KEY,
    seeker_id INTEGER,
    record_data TEXT,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Seeker residency verifications
CREATE TABLE IF NOT EXISTS tbl_seeker_residency_verifications (
    "ID" SERIAL PRIMARY KEY,
    seeker_id INTEGER,
    verification_data TEXT,
    verified_at TIMESTAMP
);

-- Seeker permission signs contracts
CREATE TABLE IF NOT EXISTS tbl_seeker_permission_signs_contracts (
    "ID" SERIAL PRIMARY KEY,
    seeker_id INTEGER,
    recruitment_id INTEGER,
    signed_at TIMESTAMP
);

-- Seeker acceptance legal terms
CREATE TABLE IF NOT EXISTS tbl_seeker_acceptance_legal_terms (
    "ID" SERIAL PRIMARY KEY,
    seeker_id INTEGER,
    terms_version VARCHAR(50),
    accepted_at TIMESTAMP DEFAULT NOW()
);

-- Seeker form RTPS
CREATE TABLE IF NOT EXISTS tbl_seeker_form_rtps (
    "ID" SERIAL PRIMARY KEY,
    seeker_id INTEGER,
    form_data TEXT,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Form RTPS rightful claimants
CREATE TABLE IF NOT EXISTS tbl_form_rtps_rightful_claimants (
    "ID" SERIAL PRIMARY KEY,
    form_id INTEGER,
    claimant_data TEXT
);

-- Entry form
CREATE TABLE IF NOT EXISTS tbl_entry_form (
    "ID" SERIAL PRIMARY KEY,
    seeker_id INTEGER,
    form_data TEXT,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Receipt service
CREATE TABLE IF NOT EXISTS tbl_seeker_receipt_service (
    "ID" SERIAL PRIMARY KEY,
    seeker_id INTEGER,
    service_data TEXT,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Job alerts
CREATE TABLE IF NOT EXISTS tbl_job_alerts (
    "ID" SERIAL PRIMARY KEY,
    seeker_id INTEGER,
    keywords VARCHAR(255),
    city INTEGER,
    industry_id INTEGER,
    email_frequency VARCHAR(50) DEFAULT 'daily',
    active SMALLINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Short list candidate interviews
CREATE TABLE IF NOT EXISTS tbl_short_list_candidate_interview (
    "ID" SERIAL PRIMARY KEY,
    candidate_id INTEGER,
    interview_date TIMESTAMP,
    status VARCHAR(50)
);

-- Expense types
CREATE TABLE IF NOT EXISTS tbl_expense_types (
    "ID" SERIAL PRIMARY KEY,
    type_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Sunat codes
CREATE TABLE IF NOT EXISTS tbl_sunat_codes (
    "ID" SERIAL PRIMARY KEY,
    code VARCHAR(100),
    description TEXT
);

-- HRMGO API connections
CREATE TABLE IF NOT EXISTS tbl_hrmgo_api_conections (
    "ID" SERIAL PRIMARY KEY,
    company_id INTEGER,
    api_url VARCHAR(255),
    api_key VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- SAP API users
CREATE TABLE IF NOT EXISTS tbl_sap_api_users (
    "ID" SERIAL PRIMARY KEY,
    company_id INTEGER,
    username VARCHAR(255),
    token TEXT,
    active SMALLINT DEFAULT 1
);

-- RYS forms
CREATE TABLE IF NOT EXISTS tbl_rys_forms (
    "ID" SERIAL PRIMARY KEY,
    form_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

CREATE TABLE IF NOT EXISTS tbl_rys_form_sections (
    "ID" SERIAL PRIMARY KEY,
    form_id INTEGER,
    section_name VARCHAR(255),
    section_order INTEGER
);

CREATE TABLE IF NOT EXISTS tbl_rys_form_questions (
    "ID" SERIAL PRIMARY KEY,
    section_id INTEGER,
    question_text TEXT,
    question_type VARCHAR(50),
    question_order INTEGER
);

-- Workflow tables
CREATE TABLE IF NOT EXISTS tbl_workflow_clients (
    "ID" SERIAL PRIMARY KEY,
    client_name VARCHAR(255),
    company_id INTEGER,
    active SMALLINT DEFAULT 1
);

CREATE TABLE IF NOT EXISTS tbl_workflow_consultants (
    "ID" SERIAL PRIMARY KEY,
    consultant_name VARCHAR(255),
    company_id INTEGER,
    active SMALLINT DEFAULT 1
);

-- Staff recruiter client companies
CREATE TABLE IF NOT EXISTS tbl_staff_recruiter_client_companies (
    "ID" SERIAL PRIMARY KEY,
    employer_id INTEGER,
    client_company_id INTEGER
);

-- Employer permission tables
CREATE TABLE IF NOT EXISTS tbl_employer_permission_business_units (
    "ID" SERIAL PRIMARY KEY,
    employer_id INTEGER,
    business_unit_id INTEGER
);

CREATE TABLE IF NOT EXISTS tbl_employer_permission_clients (
    "ID" SERIAL PRIMARY KEY,
    employer_id INTEGER,
    client_id INTEGER
);

CREATE TABLE IF NOT EXISTS tbl_employer_permission_consultants (
    "ID" SERIAL PRIMARY KEY,
    employer_id INTEGER,
    consultant_id INTEGER
);

CREATE TABLE IF NOT EXISTS tbl_employer_permission_internal_areas (
    "ID" SERIAL PRIMARY KEY,
    employer_id INTEGER,
    internal_area_id INTEGER
);

CREATE TABLE IF NOT EXISTS tbl_employer_permission_job_charges (
    "ID" SERIAL PRIMARY KEY,
    employer_id INTEGER,
    job_charge_id INTEGER
);

-- Email drafts
CREATE TABLE IF NOT EXISTS tbl_email_drafts (
    "ID" SERIAL PRIMARY KEY,
    from_email VARCHAR(255),
    to_email VARCHAR(255),
    subject VARCHAR(500),
    body TEXT,
    status VARCHAR(50) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT NOW()
);

-- Premium candidates
CREATE TABLE IF NOT EXISTS tbl_premium_candidates (
    "ID" SERIAL PRIMARY KEY,
    seeker_id INTEGER,
    start_date DATE,
    end_date DATE,
    active SMALLINT DEFAULT 1
);

-- Occupational categories
CREATE TABLE IF NOT EXISTS tbl_occupational_categories (
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

-- Employee categories
CREATE TABLE IF NOT EXISTS tbl_employee_categories (
    "ID" SERIAL PRIMARY KEY,
    category_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Kinship types
CREATE TABLE IF NOT EXISTS tbl_kinship (
    "ID" SERIAL PRIMARY KEY,
    kinship_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Seeker spouse
CREATE TABLE IF NOT EXISTS tbl_seeker_spouse (
    "ID" SERIAL PRIMARY KEY,
    seeker_id INTEGER,
    first_name VARCHAR(255),
    last_name VARCHAR(255),
    dob DATE,
    document_type_id INTEGER,
    document_number VARCHAR(100)
);

-- Mof (Manual of Functions)
CREATE TABLE IF NOT EXISTS tbl_mof (
    "ID" SERIAL PRIMARY KEY,
    company_id INTEGER,
    job_charge_id INTEGER,
    mof_data TEXT,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Ubigeo (Geographic classification for Peru)
CREATE TABLE IF NOT EXISTS tbl_ubigeo (
    "ID" SERIAL PRIMARY KEY,
    ubigeo_code VARCHAR(20),
    department VARCHAR(100),
    province VARCHAR(100),
    district VARCHAR(100)
);

-- Cap (capacity/capability)
CREATE TABLE IF NOT EXISTS tbl_cap (
    "ID" SERIAL PRIMARY KEY,
    cap_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Ways
CREATE TABLE IF NOT EXISTS tbl_ways (
    "ID" SERIAL PRIMARY KEY,
    way_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Prohibited keywords (anti-spam/scam)
CREATE TABLE IF NOT EXISTS tbl_prohibited_keywords (
    "ID" SERIAL PRIMARY KEY,
    keyword VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Scam entries
CREATE TABLE IF NOT EXISTS tbl_scam (
    "ID" SERIAL PRIMARY KEY,
    job_id INTEGER,
    reporter_id INTEGER,
    report_text TEXT,
    reported_at TIMESTAMP DEFAULT NOW()
);

-- Risk criteria
CREATE TABLE IF NOT EXISTS tbl_risk_criteria (
    "ID" SERIAL PRIMARY KEY,
    criteria_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- RRHH responsible
CREATE TABLE IF NOT EXISTS tbl_rrhh_responsible (
    "ID" SERIAL PRIMARY KEY,
    employer_id INTEGER,
    company_id INTEGER,
    active SMALLINT DEFAULT 1
);

-- Medical centers
CREATE TABLE IF NOT EXISTS tbl_medical_centers (
    "ID" SERIAL PRIMARY KEY,
    center_name VARCHAR(255),
    address TEXT,
    active SMALLINT DEFAULT 1
);

CREATE TABLE IF NOT EXISTS tbl_medical_center_locations (
    "ID" SERIAL PRIMARY KEY,
    medical_center_id INTEGER,
    city_id INTEGER,
    address VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS tbl_medical_center_emails (
    "ID" SERIAL PRIMARY KEY,
    medical_center_id INTEGER,
    email VARCHAR(255)
);

-- Story
CREATE TABLE IF NOT EXISTS tbl_stories (
    "ID" SERIAL PRIMARY KEY,
    title VARCHAR(255),
    content TEXT,
    image VARCHAR(255),
    sts SMALLINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Salaries
CREATE TABLE IF NOT EXISTS tbl_salaries (
    "ID" SERIAL PRIMARY KEY,
    job_charge_id INTEGER,
    min_salary DECIMAL(10,2),
    max_salary DECIMAL(10,2),
    currency VARCHAR(10) DEFAULT 'PEN'
);

-- Work experience categories
CREATE TABLE IF NOT EXISTS tbl_work_experience (
    "ID" SERIAL PRIMARY KEY,
    experience_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Laboral benefits
CREATE TABLE IF NOT EXISTS tbl_laboral_benefits (
    "ID" SERIAL PRIMARY KEY,
    benefit_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Institutions
CREATE TABLE IF NOT EXISTS tbl_institutions (
    "ID" SERIAL PRIMARY KEY,
    institution_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

CREATE TABLE IF NOT EXISTS tbl_institution_types (
    "ID" SERIAL PRIMARY KEY,
    type_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

CREATE TABLE IF NOT EXISTS tbl_institution_educational_types (
    "ID" SERIAL PRIMARY KEY,
    type_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

CREATE TABLE IF NOT EXISTS tbl_institution_educational_classes (
    "ID" SERIAL PRIMARY KEY,
    class_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Job competences
CREATE TABLE IF NOT EXISTS tbl_job_competences (
    "ID" SERIAL PRIMARY KEY,
    competence_name VARCHAR(255),
    active SMALLINT DEFAULT 1
);

-- Profile actions permissions
CREATE TABLE IF NOT EXISTS tbl_profile_actions_permissions (
    "ID" SERIAL PRIMARY KEY,
    profile_type VARCHAR(100),
    action_name VARCHAR(255),
    allowed SMALLINT DEFAULT 1
);

-- Menu (CMS navigation)
CREATE TABLE IF NOT EXISTS tbl_menus (
    "ID" SERIAL PRIMARY KEY,
    menu_name VARCHAR(255),
    menu_position VARCHAR(100),
    active SMALLINT DEFAULT 1
);

CREATE TABLE IF NOT EXISTS tbl_menu_pages (
    "ID" SERIAL PRIMARY KEY,
    menu_id INTEGER,
    page_title VARCHAR(255),
    page_url VARCHAR(255),
    sort_order INTEGER DEFAULT 0,
    active SMALLINT DEFAULT 1
);

-- Initial data
INSERT INTO tbl_ad_codes ("ID", ad_title) VALUES (1, 'Default Ad') ON CONFLICT DO NOTHING;

INSERT INTO tbl_countries ("ID", country_name, iso_3166_1_alpha2, iso_3166_1_alpha3, phone_code, has_operation_overall, active)
VALUES 
    (56, 'Peru', 'PE', 'PER', '51', 1, 1),
    (1, 'Argentina', 'AR', 'ARG', '54', 1, 1),
    (2, 'Bolivia', 'BO', 'BOL', '591', 1, 1),
    (3, 'Chile', 'CL', 'CHL', '56', 1, 1),
    (4, 'Colombia', 'CO', 'COL', '57', 1, 1),
    (5, 'Ecuador', 'EC', 'ECU', '593', 1, 1),
    (6, 'Mexico', 'MX', 'MEX', '52', 1, 1)
ON CONFLICT DO NOTHING;

INSERT INTO tbl_cities ("ID", city_name, country_id, sort_order, active)
VALUES
    (1, 'Lima', 56, 1, 1),
    (2, 'Arequipa', 56, 2, 1),
    (3, 'Trujillo', 56, 3, 1),
    (4, 'Cusco', 56, 4, 1),
    (5, 'Piura', 56, 5, 1)
ON CONFLICT DO NOTHING;

INSERT INTO tbl_job_industries ("ID", industry_name, top_category, active)
VALUES
    (1, 'Tecnología', 1, 1),
    (2, 'Finanzas', 1, 1),
    (3, 'Salud', 1, 1),
    (4, 'Educación', 1, 1),
    (5, 'Construcción', 0, 1),
    (6, 'Retail', 0, 1),
    (7, 'Manufactura', 0, 1),
    (8, 'Servicios', 0, 1)
ON CONFLICT DO NOTHING;
