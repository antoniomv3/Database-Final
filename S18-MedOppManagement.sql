--
-- PostgreSQL database dump
--

-- Dumped from database version 9.5.12
-- Dumped by pg_dump version 9.5.8

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: s18group08; Type: SCHEMA; Schema: -; Owner: s18group08
--

CREATE SCHEMA s18group08;


ALTER SCHEMA s18group08 OWNER TO s18group08;

SET search_path = s18group08, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: academic_plan; Type: TABLE; Schema: s18group08; Owner: s18group08
--

CREATE TABLE academic_plan (
    studentid character varying(10) NOT NULL,
    degree character varying(16) NOT NULL,
    academic_program character varying(16)
);


ALTER TABLE academic_plan OWNER TO s18group08;

--
-- Name: event; Type: TABLE; Schema: s18group08; Owner: s18group08
--

CREATE TABLE event (
    studentid character varying(10) NOT NULL,
    event_name character varying(128) NOT NULL,
    date_completed date NOT NULL
);


ALTER TABLE event OWNER TO s18group08;

--
-- Name: extra_curricular; Type: TABLE; Schema: s18group08; Owner: s18group08
--

CREATE TABLE extra_curricular (
    studentid character varying(10) NOT NULL,
    organization character varying(255) NOT NULL,
    start_date date NOT NULL,
    end_date date
);


ALTER TABLE extra_curricular OWNER TO s18group08;

--
-- Name: health_profession_school; Type: TABLE; Schema: s18group08; Owner: s18group08
--

CREATE TABLE health_profession_school (
    studentid character varying(10) NOT NULL,
    school_name character varying(255) NOT NULL,
    accepted character varying(16),
    student_choice character varying(3)
);


ALTER TABLE health_profession_school OWNER TO s18group08;

--
-- Name: health_profession_test; Type: TABLE; Schema: s18group08; Owner: s18group08
--

CREATE TABLE health_profession_test (
    studentid character varying(10) NOT NULL,
    test_name character varying(10) NOT NULL,
    test_date date NOT NULL,
    test_score integer
);


ALTER TABLE health_profession_test OWNER TO s18group08;

--
-- Name: honors_info; Type: TABLE; Schema: s18group08; Owner: s18group08
--

CREATE TABLE honors_info (
    studentid character varying(10) NOT NULL,
    participating character varying(3),
    credit_hours integer,
    course_count integer
);


ALTER TABLE honors_info OWNER TO s18group08;

--
-- Name: hs_test; Type: TABLE; Schema: s18group08; Owner: s18group08
--

CREATE TABLE hs_test (
    studentid character varying(10) NOT NULL,
    test_name character varying(10) NOT NULL,
    test_score integer
);


ALTER TABLE hs_test OWNER TO s18group08;

--
-- Name: interview; Type: TABLE; Schema: s18group08; Owner: s18group08
--

CREATE TABLE interview (
    studentid character varying(10) NOT NULL,
    contacted_student character varying(3),
    interview_date date,
    transmit_date date,
    committee_note text
);


ALTER TABLE interview OWNER TO s18group08;

--
-- Name: interview_join; Type: TABLE; Schema: s18group08; Owner: s18group08
--

CREATE TABLE interview_join (
    studentid character varying(10) NOT NULL,
    interviewerid integer NOT NULL
);


ALTER TABLE interview_join OWNER TO s18group08;

--
-- Name: interviewer; Type: TABLE; Schema: s18group08; Owner: s18group08
--

CREATE TABLE interviewer (
    interviewerid integer NOT NULL,
    first_name character varying(255),
    last_name character varying(255),
    email character varying(255)
);


ALTER TABLE interviewer OWNER TO s18group08;

--
-- Name: interviewer_interviewerid_seq; Type: SEQUENCE; Schema: s18group08; Owner: s18group08
--

CREATE SEQUENCE interviewer_interviewerid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE interviewer_interviewerid_seq OWNER TO s18group08;

--
-- Name: interviewer_interviewerid_seq; Type: SEQUENCE OWNED BY; Schema: s18group08; Owner: s18group08
--

ALTER SEQUENCE interviewer_interviewerid_seq OWNED BY interviewer.interviewerid;


--
-- Name: language_fluency; Type: TABLE; Schema: s18group08; Owner: s18group08
--

CREATE TABLE language_fluency (
    studentid character varying(10) NOT NULL,
    language character varying(255) NOT NULL
);


ALTER TABLE language_fluency OWNER TO s18group08;

--
-- Name: leadership_position; Type: TABLE; Schema: s18group08; Owner: s18group08
--

CREATE TABLE leadership_position (
    studentid character varying(10) NOT NULL,
    organization character varying(255) NOT NULL,
    "position" character varying(255) NOT NULL,
    start_date date NOT NULL,
    end_date date
);


ALTER TABLE leadership_position OWNER TO s18group08;

--
-- Name: letter_join; Type: TABLE; Schema: s18group08; Owner: s18group08
--

CREATE TABLE letter_join (
    writerid integer NOT NULL,
    studentid character varying(10) NOT NULL,
    reception_date date
);


ALTER TABLE letter_join OWNER TO s18group08;

--
-- Name: letter_writer; Type: TABLE; Schema: s18group08; Owner: s18group08
--

CREATE TABLE letter_writer (
    writerid integer NOT NULL,
    first_name character varying(255),
    last_name character varying(255),
    email character varying(255),
    phone character varying(20)
);


ALTER TABLE letter_writer OWNER TO s18group08;

--
-- Name: letter_writer_writerid_seq; Type: SEQUENCE; Schema: s18group08; Owner: s18group08
--

CREATE SEQUENCE letter_writer_writerid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE letter_writer_writerid_seq OWNER TO s18group08;

--
-- Name: letter_writer_writerid_seq; Type: SEQUENCE OWNED BY; Schema: s18group08; Owner: s18group08
--

ALTER SEQUENCE letter_writer_writerid_seq OWNED BY letter_writer.writerid;


--
-- Name: login; Type: TABLE; Schema: s18group08; Owner: s18group08
--

CREATE TABLE login (
    username character varying(255) NOT NULL,
    password character varying(255)
);


ALTER TABLE login OWNER TO s18group08;

--
-- Name: medopp_advisor; Type: TABLE; Schema: s18group08; Owner: s18group08
--

CREATE TABLE medopp_advisor (
    advisorid integer NOT NULL,
    first_name character varying(255),
    last_name character varying(255),
    email character varying(255)
);


ALTER TABLE medopp_advisor OWNER TO s18group08;

--
-- Name: packet_information; Type: TABLE; Schema: s18group08; Owner: s18group08
--

CREATE TABLE packet_information (
    application_year character varying(4) NOT NULL,
    fee numeric(4,2)
);


ALTER TABLE packet_information OWNER TO s18group08;

--
-- Name: research; Type: TABLE; Schema: s18group08; Owner: s18group08
--

CREATE TABLE research (
    studentid character varying(10) NOT NULL,
    lab_name character varying(255) NOT NULL,
    start_date date,
    end_date date,
    mentor_last_name character varying(255),
    mentor_first_name character varying(255),
    "position" character varying(255),
    volunteer character varying(3),
    hours_per_week integer
);


ALTER TABLE research OWNER TO s18group08;

--
-- Name: shadow_experience; Type: TABLE; Schema: s18group08; Owner: s18group08
--

CREATE TABLE shadow_experience (
    studentid character varying(10) NOT NULL,
    physician_last_name character varying(255) NOT NULL,
    physician_first_name character varying(255) NOT NULL,
    specialty character varying(255),
    total_hours integer
);


ALTER TABLE shadow_experience OWNER TO s18group08;

--
-- Name: student; Type: TABLE; Schema: s18group08; Owner: s18group08
--

CREATE TABLE student (
    studentid character varying(10) NOT NULL,
    first_name character varying(255),
    middle_name character varying(255),
    last_name character varying(255),
    advisorid integer NOT NULL,
    email character varying(255),
    phone character varying(20),
    date_of_birth date,
    sex character varying(25),
    ethnic_group character varying(32),
    disadvantaged character varying(3),
    first_generation character varying(3),
    military_service character varying(3),
    address character varying(255),
    city character varying(255),
    state character varying(255),
    county character varying(255),
    postal character varying(32),
    country character varying(255),
    application_year character varying(4),
    packet_received character varying(3),
    date_paid date,
    first_term character varying(6),
    application_status character varying(64),
    hs_core_gpa numeric(4,3),
    cum_gpa numeric(4,3),
    total_credit integer,
    honors_eligible character varying(3)
);


ALTER TABLE student OWNER TO s18group08;

--
-- Name: student_groups; Type: TABLE; Schema: s18group08; Owner: s18group08
--

CREATE TABLE student_groups (
    studentid character varying(10) NOT NULL,
    group_name character varying(128) NOT NULL,
    start_date date,
    end_date date
);


ALTER TABLE student_groups OWNER TO s18group08;

--
-- Name: study_abroad; Type: TABLE; Schema: s18group08; Owner: s18group08
--

CREATE TABLE study_abroad (
    studentid character varying(10) NOT NULL,
    school_abroad character varying(255) NOT NULL,
    start_date date NOT NULL,
    end_date date,
    city character varying(255),
    country character varying(255)
);


ALTER TABLE study_abroad OWNER TO s18group08;

--
-- Name: volunteer_experience; Type: TABLE; Schema: s18group08; Owner: s18group08
--

CREATE TABLE volunteer_experience (
    studentid character varying(10) NOT NULL,
    organization character varying(255) NOT NULL,
    start_date date NOT NULL,
    end_date date,
    total_hours integer,
    hours_per_week integer,
    healthcare_related character varying(3)
);


ALTER TABLE volunteer_experience OWNER TO s18group08;

--
-- Name: work_experience; Type: TABLE; Schema: s18group08; Owner: s18group08
--

CREATE TABLE work_experience (
    studentid character varying(10) NOT NULL,
    employer character varying(255) NOT NULL,
    "position" character varying(255) NOT NULL,
    start_date date NOT NULL,
    end_date date,
    hours_per_week integer,
    healthcare_related character varying(3)
);


ALTER TABLE work_experience OWNER TO s18group08;

--
-- Name: interviewerid; Type: DEFAULT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY interviewer ALTER COLUMN interviewerid SET DEFAULT nextval('interviewer_interviewerid_seq'::regclass);


--
-- Name: writerid; Type: DEFAULT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY letter_writer ALTER COLUMN writerid SET DEFAULT nextval('letter_writer_writerid_seq'::regclass);


--
-- Data for Name: academic_plan; Type: TABLE DATA; Schema: s18group08; Owner: s18group08
--

COPY academic_plan (studentid, degree, academic_program) FROM stdin;
18593759	 	 
14251211	BIOCHM_BS 	 AG
14268932	 	 
14251616	 	 
19563850	 	 
10584958	 	 
18593029	 	 
14225014	 	 
13495037	 	 
13495036	 	 
10476938	 	 
14251242	 	 
88289339	 	 
65430987	 	 
14260130	BIOSC_BS	ASU
14260130	CHEM_MIN	ASU
15902273	 	 
14260131	 	 
12653472	 	 
12863390	 	 
20144563	 	 
13420231	 	 
16132103	 	 
14501230	 	 
14261330	 	 
14553202	 	 
15520865	 	 
13655423	 	 
16326651	 	 
16631203	 	 
19461332	 	 
15562103	 	 
16128080	 	 
12336198	 	 
13356893	 	 
14421788	 	 
14233663	 	 
16623297	 	 
19636378	 	 
16826330	 	 
15896323	 	 
14261531	 	 
15869632	 	 
14251618	 	 
74823910	 	 
16394050	 	 
13366512	 	 
11202396	 	 
12485930	 	 
14251512	 	 
14261475	 	 
12425010	 	 
14216161	 	 
19572940	 	 
17394856	 	 
14251728	 	 
14830582	 	 
15603852	 	 
16938571	 	 
15860284	 	 
18966342	 	 
14260136	 	 
18573068	 	 
17592749	 	 
14126231	 	 
14261679	 	 
14157345	 	 
17338401	 	 
15152526	 	 
15839472	Bachelor of Arts	Health Science
12421616	 	 
14162162	 	 
14251241	 	 
14251012	 	 
14516262	 	 
10384759	 	 
16696231	 	 
12202331	 	 
22567883	 	 
17324496	 	 
16248936	 	 
22567889	 	 
22048856	 	 
12567734	 	 
12009256	 	 
12092742	 	 
12603352	 	 
19274885	 	 
12340092	 	 
20993691	 	 
84235564	 	 
22546691	 	 
70532996	 	 
22547668	 	 
13446789	 	 
12903476	 	 
37482018	 	 
17492739	 	 
11111111	 	 
14253123	 BIOCHM_BS	 AG
\.


--
-- Data for Name: event; Type: TABLE DATA; Schema: s18group08; Owner: s18group08
--

COPY event (studentid, event_name, date_completed) FROM stdin;
14260130	Resume Review	2017-11-23
14260130	MDAP Orientation	2017-12-25
15152526	MDAP Orientation	2017-02-15
14251211	 MDAP Orientation	2017-02-15
14253123	 MDAP Orientation	2017-05-12
\.


--
-- Data for Name: extra_curricular; Type: TABLE DATA; Schema: s18group08; Owner: s18group08
--

COPY extra_curricular (studentid, organization, start_date, end_date) FROM stdin;
14260130	Marching Mizzou	2017-08-01	\N
14260130	Ultimate Frisbee	2017-10-01	\N
17592749	 Comedy Club	2008-08-17	2013-11-15
15520865	 NSCS	2016-02-16	\N
14253123	 Club Soccer	2016-08-15	2016-12-15
\.


--
-- Data for Name: health_profession_school; Type: TABLE DATA; Schema: s18group08; Owner: s18group08
--

COPY health_profession_school (studentid, school_name, accepted, student_choice) FROM stdin;
14260130	Harvard University	Waitlisted	\N
14260130	John Hopkins University	No	\N
14260130	University of Washington	Yes	Yes
14260131	 Arizona	Yes	Yes
13860381	 Boston University	Yes	No
13860381	 Arizona State University	Yes	No
13860381	 University of Florida	Waitlisted	\N
18573068	 University of Texas at Austin	Yes	No
18573068	 University of Missouri-Columbia	Yes	Yes
17338401	University of Missouri	Yes	No
17338401	 University of South Florida	Waitlisted	\N
17338401	Kaplan University	No	No
14251211	 Albany	Yes	Yes
14251242	 Harvard	Yes	\N
13420231	 University of Illinois	Yes	Yes
16132103	 Harvard	No	No
16132103	 Northwestern	Yes	Yes
14501230	 Harvard	Yes	Yes
14501230	 Yale	Yes	No
14261330	University of Miami	Waitlisted	No
14261330	 Boston Univesity	Yes	Yes
14553202	University of Missouri-Kansas City	Yes	Yes
15520865	 University of Arizona	Yes	Yes
13655423	 University of Illinois	Yes	No
13655423	 Baylor University	Yes	Yes
16326651	Boston University	Yes	Yes
19461332	 University of Notre Dame	Yes	Yes
19461332	 Northwestern	Waitlisted	No
16128080	University of Missouri-Columbia	Yes	Yes
12336198	University of Illinois	Waitlisted	No
12336198	Missouri State University	No	No
13356893	University of Alabama	No	No
13356893	Texas A&M	Waitlisted	No
14421788	University of Missouri-Kansas City	No	No
16826330	 Princeton	Yes	No
16826330	Brown University	Yes	Yes
15896323	University of Missouri-Kansas City	Yes	No
15896323	Boston University	Yes	Yes
13366512	University of Illinois	Yes	Yes
11202396	University of Missouri-Columbia	Yes	Yes
18966342	Saint Louis University	Yes	Yes
16696231	University of Missouri-Columbia	Yes	No
16696231	Northwestern University	Yes	Yes
12202331	Boston University	Yes	No
12202331	Saint Louis University	Yes	No
12202331	University of Notre Dame	Yes	Yes
37482018	 Rutgers University	Yes	Yes
17492739	 Boston University	Yes	Yes
14253123	 Albany	Waitlisted	No
14253123	 Harvard	No	No
14253123	 John Hopkins	Yes	Yes
\.


--
-- Data for Name: health_profession_test; Type: TABLE DATA; Schema: s18group08; Owner: s18group08
--

COPY health_profession_test (studentid, test_name, test_date, test_score) FROM stdin;
14260130	MCAT	2018-01-29	500
14261531	MCAT	2017-05-25	467
74823910	MCAT	2017-05-25	479
12485930	MCAT	2017-05-25	489
14251512	MCAT	2017-05-25	485
14261475	MCAT	2017-05-25	480
14216161	MCAT	2017-05-25	474
19572940	MCAT	2017-05-25	481
14830582	MCAT	2017-05-25	485
16938571	MCAT	2017-05-25	470
18573068	MCAT	2018-02-15	468
17592749	MCAT	2017-12-15	479
14157345	MCAT	2017-05-25	475
17338401	MCAT	2017-05-25	479
15839472	MCAT	2017-12-12	495
15839472	MCAT	2018-04-15	510
12421616	MCAT	2017-05-25	473
14251211	MCAT	2017-05-25	480
14251616	MCAT	2017-05-25	460
18593029	MCAT	2017-05-25	468
14225014	MCAT	2017-05-25	470
13420231	MCAT	2018-05-04	510
16132103	MCAT	2018-04-06	500
14501230	MCAT	2017-11-26	520
14261330	MCAT	2018-02-03	495
14553202	MCAT	2018-01-20	500
15520865	MCAT	2018-01-20	505
13655423	MCAT	2017-11-26	520
16326651	MCAT	2018-02-03	495
19461332	MCAT	2018-02-03	520
16128080	MCAT	2018-02-03	505
12336198	MCAT	2018-02-03	485
13356893	MCAT	2018-02-03	515
14421788	MCAT	2018-02-03	520
16623297	MCAT	2018-02-23	510
16826330	MCAT	2018-02-03	520
15896323	MCAT	2018-02-03	510
13366512	MCAT	2018-02-03	500
11202396	MCAT	2018-02-13	495
16696231	MCAT	2018-02-03	515
14260131	DAT	2017-05-25	28
14251618	DAT	2017-05-25	28
16394050	DAT	2017-05-25	26
12425010	DAT	2017-05-25	24
17394856	DAT	2016-12-10	15
17394856	DAT	2017-12-15	21
14251728	DAT	2017-05-25	18
15603852	DAT	2017-05-25	29
15860284	DAT	2017-05-25	18
14260136	DAT	2017-05-25	15
14126231	DAT	2018-02-15	24
14261679	DAT	2017-05-25	29
15152526	DAT	2017-05-25	29
14162162	DAT	2017-05-25	25
14251241	DAT	2018-02-15	14
14251012	DAT	2017-05-25	16
14516262	DAT	2017-05-25	24
10384759	DAT	2016-09-13	23
10384759	DAT	2017-12-13	28
18593759	DAT	2017-05-25	17
14268932	DAT	2017-05-25	21
14251616	DAT	2017-05-25	28
19563850	DAT	2017-12-15	28
10584958	DAT	2018-02-15	17
14251242	DAT	2017-05-25	23
65430987	DAT	2017-03-29	22
14501230	DAT	2018-02-03	30
16326651	DAT	2018-01-26	28
13356893	DAT	2018-03-20	26
19636378	DAT	2018-01-12	25
16826330	DAT	2018-02-26	27
18966342	DAT	2018-02-03	26
12202331	DAT	2018-01-20	27
12202331	MCAT	2018-02-03	515
37482018	MCAT	2017-05-25	469
17492739	MCAT	2016-04-17	501
14253123	MCAT	2017-05-25	480
\.


--
-- Data for Name: honors_info; Type: TABLE DATA; Schema: s18group08; Owner: s18group08
--

COPY honors_info (studentid, participating, credit_hours, course_count) FROM stdin;
19563850	Yes	\N	\N
10584958		\N	\N
18593029	Yes	33	10
14225014	Yes	\N	\N
13495037		\N	\N
13495036		\N	\N
10476938		\N	\N
14251242		\N	\N
88289339		\N	\N
65430987		\N	\N
15902273		\N	\N
14260130	Yes	15	5
12653472		\N	\N
14260131	No	\N	\N
12863390		\N	\N
20144563		\N	\N
13860381	Yes	124	40
13420231	No	\N	\N
16132103	No	\N	\N
14501230	No	\N	\N
14261330	No	\N	\N
14553202	No	\N	\N
15520865	Yes	30	10
13655423	No	\N	\N
16326651	Yes	24	8
16631203		\N	\N
19461332	No	\N	\N
15562103	Yes	12	4
16128080	Yes	9	3
12336198	Yes	13	4
13356893	No	\N	\N
14421788	No	\N	\N
14233663	Yes	21	7
16623297	No	\N	\N
19636378	No	\N	\N
16826330	Yes	30	10
15896323	Yes	27	9
15869632		\N	\N
13366512	No	\N	\N
11202396	No	\N	\N
18966342	Yes	24	8
16696231	No	\N	\N
12202331	No	\N	\N
14261531	No	\N	\N
14251618	Yes	\N	\N
74823910	Yes	\N	\N
16394050	Yes	\N	\N
22567883		\N	\N
17324496		\N	\N
12485930	Yes	160	43
14251512	Yes	\N	\N
14261475	Yes	15	5
12425010	Yes	\N	\N
14216161		\N	\N
19572940		\N	\N
17394856	No	\N	\N
14251728		\N	\N
14830582	Yes	\N	\N
15603852		\N	\N
16938571		\N	\N
15860284	No	\N	\N
16248936		\N	\N
14260136		\N	\N
18573068	No	37	10
17592749	No	15	5
14126231		\N	\N
14261679	No	\N	\N
14157345		\N	\N
17338401	Yes	\N	\N
15152526	No	\N	\N
15839472	Yes	108	30
12421616	No	\N	\N
14162162	Yes	\N	\N
14251241		\N	\N
14251012		\N	\N
14516262	Yes	\N	\N
10384759	Yes	\N	\N
18593759	Yes	\N	\N
14251211	No	\N	\N
14268932		\N	\N
14251616	No	\N	\N
22567889		\N	\N
22048856		\N	\N
12567734		\N	\N
12009256		\N	\N
12092742		\N	\N
12603352		\N	\N
19274885		\N	\N
12340092		\N	\N
20993691		\N	\N
84235564		\N	\N
22546691		\N	\N
70532996		\N	\N
22547668		\N	\N
13446789		\N	\N
12903476		\N	\N
37482018	No	72	24
17492739	No	\N	\N
11111111		\N	\N
14253123	Yes	15	5
\.


--
-- Data for Name: hs_test; Type: TABLE DATA; Schema: s18group08; Owner: s18group08
--

COPY hs_test (studentid, test_name, test_score) FROM stdin;
\.


--
-- Data for Name: interview; Type: TABLE DATA; Schema: s18group08; Owner: s18group08
--

COPY interview (studentid, contacted_student, interview_date, transmit_date, committee_note) FROM stdin;
19563850	Yes	2018-06-01	\N	Love this student!
10584958	\N	\N	\N	
18593029	Yes	\N	\N	
14225014	Yes	\N	\N	
13495037	\N	\N	\N	
13495036	\N	\N	\N	
10476938	\N	\N	\N	
14251242	Yes	2018-04-15	\N	
88289339	\N	\N	\N	
65430987	\N	\N	\N	
15902273	\N	\N	\N	
14260130	Yes	2018-05-05	2018-06-05	The student did a good job.
14260131	No	\N	\N	
12653472	\N	\N	\N	
12863390	\N	\N	\N	
20144563	\N	\N	\N	
13860381	\N	\N	\N	
13420231	No	\N	\N	
16132103	No	\N	\N	
14501230	Yes	2018-05-09	\N	
14261330	Yes	2018-05-06	\N	
14553202	Yes	2018-05-10	\N	
15520865	No	\N	\N	
13655423	No	\N	\N	
16326651	No	\N	\N	
16631203	\N	\N	\N	
19461332	\N	\N	\N	
15562103	No	\N	\N	
16128080	No	\N	\N	
12336198	Yes	2018-05-10	\N	
13356893	No	\N	\N	
14421788	No	\N	\N	
14233663	Yes	2018-05-12	\N	
16623297	No	\N	\N	
19636378	Yes	2018-05-01	\N	
16826330	Yes	2018-05-10	\N	
15896323	No	\N	\N	
15869632	\N	\N	\N	
13366512	\N	\N	\N	
11202396	No	\N	\N	
18966342	No	\N	\N	
16696231	No	\N	\N	
12202331	No	\N	\N	
14261531	Yes	\N	\N	
14251618	No	\N	\N	
74823910	\N	\N	\N	
16394050	Yes	2017-06-12	2018-02-15	
22567883	\N	\N	\N	
12485930	Yes	2018-09-12	2018-04-20	
14251512	\N	\N	\N	
14261475	Yes	2018-03-25	2018-05-25	
12425010	Yes	\N	\N	
14216161	Yes	\N	\N	
19572940	\N	\N	\N	
17394856	Yes	\N	\N	
14251728	\N	\N	\N	
14830582	\N	\N	\N	
15603852	\N	\N	\N	
16938571	\N	\N	\N	
15860284	\N	\N	\N	
17324496	\N	\N	\N	
14260136	\N	\N	\N	
18573068	Yes	2018-05-12	\N	
17592749	\N	\N	\N	
14126231	Yes	\N	\N	
14261679	\N	\N	\N	
14157345	No	\N	\N	
17338401	\N	\N	\N	
15152526	Yes	\N	\N	
15839472	No	\N	\N	Need to contact student as soon as possible.
12421616	No	\N	\N	
14162162	No	\N	\N	
14251241	No	\N	\N	
14251012	Yes	\N	\N	
14516262	Yes	\N	\N	
10384759	\N	\N	\N	
18593759	Yes	\N	\N	
14251211	Yes	2018-03-25	2018-05-25	Very strong applicant.
14268932	\N	\N	\N	
14251616	Yes	2018-02-20	\N	
16248936	\N	\N	\N	
22567889	\N	\N	\N	
22048856	\N	\N	\N	
12567734	\N	\N	\N	
12009256	\N	\N	\N	
12092742	\N	\N	\N	
12603352	\N	\N	\N	
19274885	\N	\N	\N	
12340092	\N	\N	\N	
20993691	\N	\N	\N	
84235564	\N	\N	\N	
22546691	\N	\N	\N	
70532996	\N	\N	\N	
22547668	\N	\N	\N	
13446789	\N	\N	\N	
12903476	\N	\N	\N	
37482018	No	\N	\N	
17492739	Yes	2018-05-20	\N	
11111111	\N	\N	\N	
14253123	Yes	2018-05-25	\N	 
\.


--
-- Data for Name: interview_join; Type: TABLE DATA; Schema: s18group08; Owner: s18group08
--

COPY interview_join (studentid, interviewerid) FROM stdin;
14126231	9
14126231	5
14126231	4
14261679	9
14261679	5
14261679	2
14261679	10
14157345	10
14157345	6
14157345	3
15152526	9
15152526	4
15152526	10
15839472	3
12421616	8
12421616	6
12421616	3
12421616	1
12421616	10
14162162	6
14162162	8
14251241	7
14251241	3
14251241	9
14251012	2
14251012	6
14251012	10
14260130	2
14260130	4
14260130	5
14260130	6
14516262	9
14516262	5
14516262	2
14516262	1
18593759	2
14251211	9
14251211	3
14251211	10
14251211	2
14251616	8
14251616	3
14251616	1
14251616	2
14251616	9
19563850	3
18593029	9
14225014	8
14225014	1
14225014	9
14251242	9
14251242	3
14251242	7
14253123	10
14253123	1
14253123	6
14261531	1
14261531	8
14261531	10
14251618	8
16394050	4
12485930	4
14251512	8
14251512	6
14251512	2
14261475	10
14261475	6
14261475	3
12425010	8
12425010	2
12425010	1
12425010	9
14216161	9
14216161	4
14216161	10
14216161	1
17394856	3
15860284	8
14260136	8
14260136	3
14260136	9
\.


--
-- Data for Name: interviewer; Type: TABLE DATA; Schema: s18group08; Owner: s18group08
--

COPY interviewer (interviewerid, first_name, last_name, email) FROM stdin;
1	Nathan	Fillion	mal@frfly.com
2	Summer	Glau	river@frfly.com
4	Jewel	Staite	kaylee@frfly.com
5	Alan	Tudyk	wash@frfly.com
6	Gina	Torres	zoe@frfly.com
7	Adam	Baldwin	jayne@frfly.com
8	Sean	Maher	simon@frfly.com
9	Ron	Glass	book@frfly.com
10	Joss	Whedon	leafonthewind@frfly.com
3	Morena	Baccarin	inara@frfly.com
\.


--
-- Name: interviewer_interviewerid_seq; Type: SEQUENCE SET; Schema: s18group08; Owner: s18group08
--

SELECT pg_catalog.setval('interviewer_interviewerid_seq', 11, true);


--
-- Data for Name: language_fluency; Type: TABLE DATA; Schema: s18group08; Owner: s18group08
--

COPY language_fluency (studentid, language) FROM stdin;
15152526	 
15839472	English
12421616	 
14162162	 
14251241	 
14251012	 
14516262	 
10384759	 English
18593759	 English
14251211	 English
14268932	 
14251616	 
19563850	 English
10584958	 
18593029	 English
14225014	 
13495037	 
13495036	 
10476938	 
14260130	English
14260130	Spanish
14251242	 
14260131	 English
88289339	 English
65430987	 English
65430987	Russian
15902273	 Japanese
13860381	English 
15902273	 English
12653472	 English
12863390	 Spanish
12863390	 English
20144563	 
13420231	 English
13420231	 German
16132103	 English
14501230	 English
14501230	 Spanish
14261531	 
14261330	 Spanish
14251618	 
74823910	 
16394050	English
16394050	Chinese
14261330	 English
12485930	 English
14251512	 
14261475	 English
14261475	 Spanish
12425010	 
14216161	 
19572940	English 
17394856	English
14251728	 
14830582	 English, Spanish
15603852	 
16938571	 
15860284	English
14260136	 
18573068	 English
17592749	 English
14126231	 
14261679	 
14157345	 
17338401	 English
17338401	 Japanese
14553202	 English
15520865	 English
15520865	 Mandarin
13655423	 English
16326651	 English
16326651	 Korean
16631203	 English
19461332	 English
15562103	 English
15562103	 Mandarin
16128080	 English
12336198	 English
12336198	 French
13356893	English
14421788	 English
14233663	 English
14233663	 Spanish
16623297	 English
19636378	 English
16826330	 English
15896323	 English
15869632	 English
15869632	 Polish
13366512	 English
11202396	 English
18966342	 English
16696231	 English
12202331	 English
22567883	 English
17324496	 English
17324496	 Hindi
16248936	 English
22567889	 Spanish
22567889	 English
22048856	Korean
22048856	 English
12567734	 English
12009256	 English
12092742	 English
12603352	 English
12603352	 Italian
19274885	 English
12340092	 English
20993691	 Russian
20993691	 English
84235564	 English
22546691	 English
70532996	 English
22547668	 English
13446789	 English
12903476	 English
37482018	 
17492739	 English
11111111	 English
14253123	 English
14253123	 Spanish
\.


--
-- Data for Name: leadership_position; Type: TABLE DATA; Schema: s18group08; Owner: s18group08
--

COPY leadership_position (studentid, organization, "position", start_date, end_date) FROM stdin;
14260130	Marching Mizzou	Section Leader	2018-01-01	\N
14253123	Honors Ambassadors	Vice President	2018-08-15	\N
\.


--
-- Data for Name: letter_join; Type: TABLE DATA; Schema: s18group08; Owner: s18group08
--

COPY letter_join (writerid, studentid, reception_date) FROM stdin;
2	17592749	\N
10	14126231	2017-02-05
4	14126231	2017-02-05
1	14126231	2017-09-25
11	14261679	\N
6	14261679	\N
2	14261679	\N
10	14157345	\N
4	14157345	2017-02-05
1	14157345	\N
4	17338401	2018-05-20
9	15152526	\N
6	15152526	\N
2	15152526	\N
9	15839472	\N
8	12421616	\N
11	12421616	\N
1	12421616	\N
11	14162162	\N
6	14162162	\N
11	14251241	\N
10	14251241	2018-12-15
10	14251012	\N
8	14251012	\N
3	14251012	\N
10	14516262	\N
2	14260130	2018-04-03
4	14260130	2018-04-04
6	14260130	2018-04-05
8	14260130	2018-04-06
4	14516262	\N
2	14516262	\N
5	13860381	\N
3	18593759	\N
11	14251211	\N
3	14251211	2017-09-25
1	14251211	\N
9	14251616	\N
5	14251616	\N
2	14251616	\N
10	14251616	\N
6	14251616	\N
4	14251616	2018-12-15
9	19563850	2017-11-12
2	18593029	\N
2	14225014	\N
9	14225014	\N
11	14225014	\N
10	14251242	\N
5	14251242	\N
2	14251242	\N
3	13420231	2018-04-05
9	16132103	2018-05-06
8	14501230	2018-02-16
5	14261330	\N
5	14553202	\N
2	15520865	2018-05-04
7	13655423	\N
4	16326651	2018-02-15
8	19461332	\N
3	15562103	2018-02-16
10	16128080	\N
4	12336198	2018-03-04
1	13356893	\N
2	14421788	2018-02-20
4	14233663	2018-02-24
8	14261531	\N
10	14261531	2018-01-24
6	14261531	\N
10	14251618	\N
6	16394050	\N
4	16623297	\N
4	19636378	2018-02-26
6	12485930	\N
11	14251512	\N
10	14251512	\N
6	14251512	2017-02-05
7	14251512	\N
10	14261475	\N
3	14261475	\N
1	14261475	\N
11	12425010	\N
7	12425010	2017-09-25
4	12425010	2017-02-05
2	12425010	2018-12-15
7	14216161	\N
9	14216161	\N
2	14216161	\N
10	17394856	\N
11	14251728	\N
9	14251728	\N
3	14251728	\N
5	15860284	\N
6	16826330	2018-02-13
5	15896323	\N
2	13366512	2018-03-17
2	14260136	\N
11	14260136	\N
7	14260136	\N
10	18573068	\N
5	11202396	\N
6	18966342	\N
5	16696231	2018-02-05
4	12202331	2018-04-04
3	37482018	2018-05-17
5	17492739	\N
10	14253123	2017-02-05
3	14253123	\N
1	14253123	\N
\.


--
-- Data for Name: letter_writer; Type: TABLE DATA; Schema: s18group08; Owner: s18group08
--

COPY letter_writer (writerid, first_name, last_name, email, phone) FROM stdin;
1	Yancy	Yellow	yel@rdogs.com	123-456-7890
2	Brianna	Blue	blu@rdogs.com	123-456-7891
3	Penny	Pink	pnk@rdogs.com	123-456-7892
4	George	Green	grn@rdogs.com	123-456-7893
5	Richard	Red	red@rdogs.com	123-456-7894
6	Oscar	Orange	ora@rdogs.com	123-456-7895
7	Vanessa	Violet	vio@rdogs.com	123-456-7896
8	Ian	Indigo	ind@rdogs.com	123-456-7897
9	Whitney	White	wht@rdogs.com	123-456-7898
10	Ben	Black	blk@rdogs.com	123-456-7899
11	Mason	Briggs	briggs@gmail.com	\N
\.


--
-- Name: letter_writer_writerid_seq; Type: SEQUENCE SET; Schema: s18group08; Owner: s18group08
--

SELECT pg_catalog.setval('letter_writer_writerid_seq', 12, true);


--
-- Data for Name: login; Type: TABLE DATA; Schema: s18group08; Owner: s18group08
--

COPY login (username, password) FROM stdin;
test	$1$.9EidZBI$yvqnuzmZBHI7rt3dhxiX21
\.


--
-- Data for Name: medopp_advisor; Type: TABLE DATA; Schema: s18group08; Owner: s18group08
--

COPY medopp_advisor (advisorid, first_name, last_name, email) FROM stdin;
2	Susan	Geisert	geiserts@missouri.edu
1	Darcy	Holtgrave	holtgraved@missouri.edu
\.


--
-- Data for Name: packet_information; Type: TABLE DATA; Schema: s18group08; Owner: s18group08
--

COPY packet_information (application_year, fee) FROM stdin;
2017	35.00
2018	35.00
\.


--
-- Data for Name: research; Type: TABLE DATA; Schema: s18group08; Owner: s18group08
--

COPY research (studentid, lab_name, start_date, end_date, mentor_last_name, mentor_first_name, "position", volunteer, hours_per_week) FROM stdin;
14260130	Alcohol, Health and Behavior	2017-09-05	\N	Sher	Kenneth	Undergrad Researcher	No	15
22567883	Dental Institute	\N	\N	Kim	 Lee	Research Assistant	No	20
14253123	Plants	2015-02-15	\N	Shelpers	Maya	Tech	Yes	15
\.


--
-- Data for Name: shadow_experience; Type: TABLE DATA; Schema: s18group08; Owner: s18group08
--

COPY shadow_experience (studentid, physician_last_name, physician_first_name, specialty, total_hours) FROM stdin;
14260130	Nguyen	Minh	Family Medicine	30
14253123	 Harris	Mary	Pediatrician	25
14253123	 Washington	Clint	Pediatrician	15
\.


--
-- Data for Name: student; Type: TABLE DATA; Schema: s18group08; Owner: s18group08
--

COPY student (studentid, first_name, middle_name, last_name, advisorid, email, phone, date_of_birth, sex, ethnic_group, disadvantaged, first_generation, military_service, address, city, state, county, postal, country, application_year, packet_received, date_paid, first_term, application_status, hs_core_gpa, cum_gpa, total_credit, honors_eligible) FROM stdin;
13495037	Sheray		Williams	2			\N	Female	African American										2018	Yes	\N			\N	\N	\N	
13495036	Matthew		Wilson	1			\N	Male	White										2018		\N			\N	\N	\N	
74823910	Steven		Bradley	1	stevenbradley@yahoo.com	555-324-9807	1992-12-07	Male	White	No	No	No				No			2018		\N	FS2015	Application Submitted	3.789	3.501	\N	Yes
14260131	Jacob	P	Anderson	2	gworm@gmail.com	698-143-3605	1990-06-18	Male	African American	Yes	Yes	Yes	241 Priscott Ave	Topeka	Kansas	Yes	67953	USA	2017	Yes	2018-08-25	FS2016	Gap Year	3.990	3.860	45	Yes
12485930	Abigail		Conley	1	conleya124@gmail.com	555-323-2345	1994-03-08	Female	White	Yes	No	No				No			2018		\N	SP2017	Gap Year	3.985	4.104	4	Yes
19572940	Katelyn		Fisher	2	katefish@aol.com	458-782-0012	1994-04-04	Female	White	No	Yes	No				No					\N			\N	\N	\N	
17592749	Junie B.		Jones	2	juniebjones@gmail.com	314-850-2949	1992-01-05	Female	White	No	Yes	No		Mount Holly Township	New Jersey	No			2018	Yes	2013-05-06	SP2015	Applying this Cycle	3.236	3.678	4	No
15839472	Ashley	Katelyn	Martin	1	akm356@mail.missouri.edu		1993-07-01	Female	White	No	No	No				No			2018	Yes	2013-07-10	FS2017	Applying this Cycle	2.865	2.850	4	No
10384759	Richard	K	Phillips	2	richkphil@gmail.com		1988-02-02	Male	White	No	No	No				No			2018	Yes	2018-05-20	SP2019	Application Submitted	\N	\N	\N	Yes
10584958	Eunice		Thomas	1			\N	Female	White										2018		\N	SP2021	Application Submitted	\N	\N	\N	
18593029	William		Timbow	1	willytim@gmail.com	555-389-5660	1994-05-10	Male	White	No	No	No				No		United States	2018	No	\N	FS2020	Application Submitted	\N	\N	\N	No
13860381	Marcus		Long	1	mmmlong@mail.missouri.edu	555-234-0978	1993-04-07	Male	African American	No	No	No				No			2018	Yes	\N	SP2018	Application Submitted	3.882	3.740	4	Yes
14251618	Zoe		Boomer	1			\N	Female	African American	Yes	Yes	No				No			2018	Yes	\N	FS2016	Application Submitted	\N	\N	\N	Yes
16394050	Ming		Chao	1	mingchao@yahoo.com	555-395-2948	1992-12-09	Female	Asian	No	No	No				No			2018	Yes	2017-11-11	FS2018	Application Submitted	3.991	3.678	4	Yes
14261475	Mario		Fargo	1			\N	Male	Hispanic	Yes	No	Yes				Yes			2018	Yes	2017-08-11	FS2016	Application Submitted	\N	\N	\N	Yes
12425010	Jasmine		Fernandez	1			\N	Female	Hispanic	Yes	Yes	Yes				Yes			2018	Yes	\N	FS2016	Application Submitted	\N	\N	\N	Yes
17394856	Crystal		Geyser	2	crystalgeyser@gmail.com		1994-09-04	Female	African American	No	Yes	No				No			2018	No	\N	SP2020	Applying this Cycle	3.604	3.700	4	Yes
14830582	Santiego		Hernandez	1	sshernz@gmail.com	555-291-3019	1994-06-08	Male	Hispanic	No	Yes	No				No			2018	Yes	\N		Gap Year	3.222	3.311	4	No
15603852	Mika		Hopi	1			\N	Female	Native American										2018		\N			\N	\N	\N	
16938571	Sying		Hsu	2	syinghsu@aol.com		\N	Female	Asian	No	No	No				No			2018	No	\N	FS2021	Gap Year	\N	\N	\N	
14260136	Samuel		Johnson	1			\N	Male	African American	Yes	No	No				No			2018	Yes	\N	FS2016	Gap Year	\N	\N	\N	
18573068	Chelsey	T.	Johnson	2	jojochels@gmail.com	496-294-0049	1996-04-30	Female	African American	No	No	Yes				Yes			2018	No	\N	FS2018	Application Submitted	3.530	3.521	4	No
14126231	Krista		Lang	1			\N	Female	Asian	Yes	Yes	No				No					\N			\N	\N	\N	
17338401	Xiao		Lin	1	xlin2e@mail.missouri.edu	555-830-2293	1993-08-23	Male	Asian	No	Yes								2018	No	\N	SP2016	Application Submitted	3.790	3.602	4	Yes
12421616	Bria		Marva	2			\N	Female	African American	Yes	No	No				No			2018	No	\N	FS2017	Application Submitted	\N	\N	\N	Yes
14251241	Rebecca		Miller	1			\N	Female	African American	Yes	No								2017	Yes	\N	FS2016		\N	\N	\N	No
19563850	Clark	D.	Stones	2	cstones@yahoo.com	555-234-8745	1995-09-18	Male	African American		Yes		4459 Sugarcane Rd					United States of America	2018	Yes	2018-02-15	FS2018	Application Submitted	3.467	3.700	4	Yes
10476938	Mila		Cecil	2			\N	Female	Hispanic										2018		\N			\N	\N	\N	
14225014	Harry		Truman	2			\N	Male	White	No	Yes	No				No			2018	Yes	\N	SP2016		\N	\N	\N	Yes
14260130	Griffin	Andrew	McElroy	1	30under30@gmail.com	697-123-4205	1987-04-17	Male	White	No	Yes	No	59 Roker Street	Austin	Texas	No	42135	USA	2018	Yes	2018-08-25	FS2017	Application Submitted	4.010	3.860	15	Yes
14261531	Tony		Bellugio	1			\N	Male	White	No	Yes	No				No			2018	Yes	\N	FS2017		\N	\N	\N	Yes
14251512	Cory		Dimer	1			\N	Male	White	Yes	Yes	No				No			2018	No	\N	FS2016	Applying this Cycle	\N	\N	\N	Yes
14216161	Mitch		Firth	1			\N	Male	White	No	No	No				No			2017	Yes	2018-08-25	FS2017	Applying this Cycle	\N	\N	\N	No
14251728	Fillis		Green	1			\N	Other	White	No	Yes								2017	No	\N	FS2017		3.563	3.273	\N	No
15860284	Bryan		James	2	bjames11@gmail.com		\N	Prefer not to say	White	No	No	No				No			2018	Yes	2017-03-30	FS2019	Gap Year	2.782	3.462	4	No
14261679	John		Lawson	1			\N	Male	White	No	No	No				No			2018	No	\N	FS2016	Gap Year	\N	\N	\N	Yes
14157345	Maria		Layton	1			\N	Female	White	No	Yes	No				No					\N			\N	\N	\N	
15152526	Lorenzo		Manafort	2			\N	Male	White	No	Yes	No				No			2017	No	\N	FS2017	Application Submitted	\N	\N	\N	Yes
14162162	Justin		McNeill	2			\N	Male	White	No	Yes	No				No			2018	Yes	\N	FS2017	Applying this Cycle	\N	\N	\N	Yes
14251012	Travis		O'Neill	2			\N	Male	White	No	Yes	Yes				Yes					\N			\N	\N	\N	No
14516262	Jennifer		Paz	2			\N	Female	White	No	Yes	No				No			2018	Yes	2017-08-11	FS2017	Application Submitted	\N	\N	\N	Yes
18593759	Ben		Simons	1	benny124@yahoo.com	555-958-0019	1995-07-30	Male	White	Yes	No	No				No			2018	No	\N	FS2019	Applying this Cycle	2.981	3.190	4	No
14251211	Jessica		Sinclair	1	jones@gmail.com	515-512-5125	1987-12-05	Female	White	No	No	Yes	851 Jefferson Ave	Albany	New York	Yes		USA	2018	Yes	2017-08-11	FS2016	Application Submitted	3.980	3.875	\N	Yes
14268932	Todd		Smith	2			\N	Male	White	No	Yes								2018	No	\N	FS2016		\N	\N	45	No
14251616	Richard		Smithers	2			\N	Male	White	No	Yes	Yes				Yes			2018	Yes	\N	FS2017	Application Submitted	\N	\N	\N	Yes
14251242	Ake		Ambrocio	1			\N	Male	Asian	Yes	No										\N			\N	\N	\N	
88289339	Grayson		Grieves	1	gray33@gmail.com		\N	Male	White	No	No	Yes	365 north ave			Yes					\N		Gap Year	3.200	2.900	\N	
65430987	Hannah	C	Strieliza	2	lovelyg1rl@aol.com		\N		White	No	No	No				No			2017	No	\N	FS2017	Application Submitted	3.293	3.672	\N	
15902273	Rin		Matsuoka	1	sharkbait@gmail.com		\N	Male	Asian	No	No	No				No			2015	No	\N		Application Submitted	3.299	3.760	\N	
12653472	Amanda		Johnson	1	manda-panda14@gmail.com		\N		Native American	No	Yes	No				No				No	\N		Application Submitted	2.478	3.009	\N	
12863390	Gina		Gonzales	1	gonzales.gina@gmail.com		\N	Female	Hispanic	No	Yes	No				No			2015	No	\N		Gap Year	3.012	2.234	\N	
20144563	Camryn		Smith	1	smithce@gmail.com		\N	Other	Native American	No	Yes	Yes				Yes				No	\N		Applying this Cycle	2.224	2.996	\N	
13420231	Thomas		Rudolph	2	tar142@mail.missouri.edu	740-992-8433	1996-11-05	Male	African American	No	Yes	No	702 Kildeer Drive	Pomeroy	Ohio	No	45769	United States	2018	No	\N	FS2015	Application Submitted	3.863	3.855	60	Yes
16132103	Anthony	J	Davis	1	ajds5s@mail.missouri.edu	646-406-3256	1997-05-11	Male	White	No	No	Yes	4362 Elm Drive	Garden City	New York	Yes	11530	United States	2018	No	\N	FS2016	Application Submitted	3.564	3.859	45	Yes
14501230	Morgan		McCants	2	mjm465@mail.missouri.edu	347-571-4473	1996-05-17	Female	African American	No	No	Yes	2663 Dancing Dove Lane	Long Island City	New York	Yes	11101	United States	2018	No	\N	FS2015	Applying this Cycle	3.646	3.946	61	No
14261330	Kristen		Scott	1	kas599@mail.missouri.edu	919-544-7234	1997-12-04	Female	Hispanic	No	No	No	781 Jennifer Lane	Durham	North Carolina	No	27713	United States	2018	Yes	2018-02-21	FS2016	Application Submitted	3.756	3.841	46	Yes
14553202	Tracy	P	Morgan	1	tap434@mail.missouri.edu	949-680-7814	1996-12-12	Male	African American	No	Yes	Yes	3066 Elk Street	Irvine	California	Yes	92618	United States	2018	Yes	2018-03-18	FS2015	Application Submitted	3.878	3.695	70	No
15520865	Emma		Lathrup	2	eal456@mail.missouri.edu	315-667-8592	1996-05-07	Female	White	Yes	Yes	No	2977 Confederate Drive	Utica	New York	No	13502	United States	2018	No	\N	FS2015	Application Submitted	3.956	3.944	70	Yes
13655423	James		Parker	1	jam235@mail.missouri.edu	315-667-8592	1996-12-16	Male	White	No	No	No	4230 Honeysuckle Lane	Anacortes	Washington	No	98221	United States	2018	Yes	2018-02-20	FS2015	Application Submitted	3.561	3.712	57	Yes
16326651	Peter	J	Back	2	pjb324@mail.missouri.edu	415-386-1596	1996-10-12	Male	Asian	Yes	Yes	No	5 Roosevelt Street	San Francisco	California	No	94118	United States	2018	No	\N	FS2015	Application Submitted	3.512	3.712	58	Yes
16631203	Trevor		Stills	2	tjs233@mail.missouri.edu	415-386-1596	1996-11-21	Male	African American	No	Yes	Yes	117 Pick Street	Marvel	Colorado	Yes	81329	United States			\N			\N	\N	\N	
19461332	Tiffany		Perry	2	tsp323@mail.missouri.edu	708-278-7654	1996-08-09	Female	White	No	No	No	2595 Rose Street	Schaumburg	Illinois	No	60173	United States	2018	No	\N	FS2015	Application Submitted	3.566	3.825	63	Yes
15562103	Jeremy		Lin	1	jal234@mail.missouri.edu	559-921-7661	1997-12-12	Male	Asian	No	No	No	2468 Half and Half Drive	Fresno	California	No	93721	United States	2018	No	\N	FS2016	Application Submitted	3.755	3.556	42	Yes
16128080	Jason		Jones	1	jej256@mail.missouri.edu	559-921-7661	1997-04-27	Male	White	No	Yes	No	764 Boone Street	Corpus Christi	Texas	No	78476	United States	2018	Yes	2018-02-26	FS2016	Applying this Cycle	3.778	3.766	45	Yes
12336198	Diane		Lawler	1	dol356@mail.missouri.edu	337-639-2921	1996-10-20	Female	White	No	No	No	1296 Bridge Avenue	Oberlin	Louisiana	No	70655	United States	2018	Yes	2018-03-20	FS2015	Application Submitted	3.566	3.789	62	Yes
13356893	Tristan		Baker	2	tab234@mail.missouri.edu	414-651-5650	1998-08-01	Male	African American	No	Yes	No	4819 Woodlawn Drive	New Berlin	Wisconsin	No	53151	United States	2018	No	\N	FS2017	Applying this Cycle	3.599	3.799	36	Yes
14421788	Trey		Simms	2	tim123@mail.missouri.edu	860-437-5594	1996-03-20	Male	White	Yes	No	No	3894 Lochmere Lane	New London	Connecticut	No	06320	United States	2018	No	\N	FS2015	Application Submitted	3.457	3.689	60	No
14233663	Austin		Dazey	2	aed566@mail.missouri.edu	860-437-5594	1996-11-20	Male	White	Yes	No	No	4217 Eastland Avenue	Philadelphia	Mississippi	No	39350	United States	2018	Yes	2018-02-20	FS2015	Application Submitted	3.653	3.727	66	Yes
16623297	Oliver		Hartman	2	odh9j4@mail.missouri.edu	254-751-3468	1997-07-30	Male	Hispanic	No	No	No	920 Clair Street	Waco	Texas	No	76710	United States	2018	No	\N	FS2016	Applying this Cycle	3.434	3.695	42	Yes
19636378	Anniken		Walker	1	aaw32s@mail.missouri.edu	215-439-1146	1997-04-23		African American	No	Yes	No	328 Wakefield Street	Wayne	Pennsylvania	No	19088	United States	2018	Yes	2018-02-28	FS2016	Applying this Cycle	3.552	3.712	39	No
16826330	Ashley		McFarland	2	apm23e@mail.missouri.edu	210-856-9348	1996-07-07	Female	White	No	No	No	1813 Cinnamon Lane	San Antonio	Texas	No	78205	United States	2018	Yes	2018-02-12	FS2015	Application Submitted	3.899	3.925	72	Yes
15896323	Kathleen		Sothers	2	kss2z4@mail.missouri.edu	678-703-8241	1996-08-10	Female	African American	No	Yes	No	12 Kuhl Avenue	Atlanta	Georgia	No	30346	United States	2018	Yes	2018-01-01	FS2015	Application Submitted	3.882	3.916	75	Yes
15869632	David		Bobek	1	dbbl42@mail.missouri.edu	770-206-0952	1996-04-05	Male	White	No	Yes	No	901 Fowler Avenue	Dunwoody	Georgia	No		United States			\N			\N	\N	\N	
13366512	Chris		Neal	1	con2s1@mail.missouri.edu	301-497-6515	1996-11-12	Male	White	No	No	No	4233 C Street	Boston	Massachusetts	No	02110	United States	2018	No	\N	FS2015	Applying this Cycle	3.561	3.776	60	Yes
11202396	Dennis		Frith	2	ddf23c@mail.missouri.edu	415-844-6855	1996-07-15	Male	White	No	Yes	No	3151 Shady Pines Drive	Elizabethtown	Kentucky	No	42701	United States	2018		\N	FS2015	Gap Year	3.555	3.826	54	Yes
18966342	Amy		Douglas	2	apdw3c@mail.missouri.edu	270-989-8391	1996-10-30	Female	Asian	No	No	Yes	407 Beechwood Drive	Bridgeville	Pennsylvania	Yes	15017	United States	2018	Yes	2018-01-01	FS2015	Applying this Cycle	3.669	3.892	69	Yes
16696231	John		Trumane	1	jatu4c@mail.missouri.edu	609-556-3472	1997-06-29	Male	African American	No	No	No	1927 Lincoln Street	Camden	New Jersey	No	08102	United States	2018	No	\N	FS2015	Application Submitted	3.522	3.648	48	No
12202331	Mitchell		Paul	2	map23d@mail.missouri.edu	281-652-6820	1996-11-09	Male	White	No	No	No	1459 Chapel Street	Houston	Texas	No	77030	United States	2018	No	\N	FS2015	Application Submitted	3.559	3.679	48	Yes
22567883	Mathew		Briggs	2	briggs.m@gmail.com		\N		Asian	No	No	No				No			2017	Yes	\N		Gap Year	3.992	3.865	\N	
17324496	Lilly		Singh	1	superwoman@gmail.com		\N	Female	Asian	No	No	No				No			2017	Yes	\N		Applying this Cycle	3.652	3.798	\N	
16248936	Alina		Beckett	1	beckettali@gmail.com		\N	Female	Native American	No	Yes	Yes				Yes			2018	Yes	\N		Application Submitted	2.122	2.780	\N	
22567889	Violet		Fernandez	2	fernandezvi@gmail.com		\N		Hispanic	Yes	Yes	Yes				Yes			2018	No	\N		Application Submitted	3.290	3.252	\N	
22048856	Sunni		Singleton	1	sunni3smile@gmail.com		\N	Other	Asian	Yes	Yes	No				No			2017	No	\N		Application Submitted	3.012	2.794	\N	
12567734	Robyn		Arlington	1	robyn3324@gmail.com		\N	Female	Native American	Yes	Yes	No				No			2018	No	\N		Application Submitted	3.299	3.350	\N	
12009256	Klaus		Baudelaire	1	baudelaire.k@gmail.com		\N		White	No	No	No				No			2018	Yes	\N		Applying this Cycle	4.000	4.000	\N	
12092742	Elise		Berger	1	berger.e@gmail.com		\N	Female	White	Yes	No	No				No			2017	Yes	\N		Applying this Cycle	2.944	2.726	\N	
12603352	David		Crenshaw	2	dcartist62@gmail.com		\N	Male	White	No	No	No				No				No	\N		Applying this Cycle	3.108	2.623	\N	
19274885	Marina		DeSantis	1	desantis.marina@gmail.com		\N	Female	White	No	No	No				No				No	\N		Gap Year	3.290	3.160	\N	
12340092	Regina		George	2	queen2004@aol.com		\N	Female	White	No	No	No				No			2014	No	\N		Application Submitted	2.961	2.755	\N	
20993691	Yuri		Plisetsky	1	yuri.plisetsky@gmail.com		\N	Male	White	No	No	No				No			2017	No	\N			2.567	2.823	\N	
84235564	Patricia		Smithers	2	smithers.pat@gmail.com		\N	Female	White	Yes	No	Yes				Yes			2018	No	\N		Applying this Cycle	3.200	3.602	\N	
22546691	Jacob		Thomlin	1	thomlinj@gmail.com		\N	Male	White	Yes	No	No				No				No	\N			2.788	2.922	\N	
70532996	Kiera		Caldwell	2			\N	Female	African American	No	Yes	No				No				No	\N			2.996	3.554	\N	
22547668	Reggie		Grey	2	grey1joy@aol.com		\N	Male	African American	Yes	Yes	No				No				No	\N		Application Submitted	3.104	3.202	\N	
13446789	Georgina		Lackland	2	msgeorgina1995@gmail.com		\N	Female	African American	No	Yes	Yes				Yes			2016	No	\N		Gap Year	3.224	3.560	\N	
12903476	Nellie	C	Porter	2	rep0rterg1rl@gmail.com		\N	Female	African American	No	No	No				No				No	\N			3.814	3.922	\N	
37482018	Danielle		Lipley	1	dd4lip@gmail.com	892-777-4590	1993-01-15	Female	White										2018	Yes	\N	SP2016	Applying this Cycle	3.523	3.789	4	Yes
17492739	Tyler		Brown	1	tbb289@mail.missouri.edu		\N	Male	White	No	No	Yes				Yes	65201	United States	2018	Yes	2018-03-23	FS2018	Application Submitted	2.956	3.000	4	No
11111111	Test		Student	1	thisisatest@gmail.com	012-345-6789	2018-05-10	Prefer not to say	Other	No	No	No	Arts & Sciences 114	Columbia	MO	No	65201	United States			\N			\N	\N	\N	
14253123	Kristina		Sanchez	2	kps1c3@mail.missouri.edu	636-112-5442	1996-02-04	Female	Hispanic	Yes	Yes	No	636 Washington Ave	Columbia	MO	No	65201	USA	2018	Yes	2017-08-11	FS2016	Application Submitted	3.690	3.560	60	Yes
\.


--
-- Data for Name: student_groups; Type: TABLE DATA; Schema: s18group08; Owner: s18group08
--

COPY student_groups (studentid, group_name, start_date, end_date) FROM stdin;
14260130	MSA	2017-09-01	2018-05-01
14260130	MCA	2017-10-01	\N
14253123	 MACS	2016-08-15	2017-05-30
14253123	 SHPE	2016-08-20	\N
\.


--
-- Data for Name: study_abroad; Type: TABLE DATA; Schema: s18group08; Owner: s18group08
--

COPY study_abroad (studentid, school_abroad, start_date, end_date, city, country) FROM stdin;
14260130	Cambridge	2018-05-25	2018-08-05	Cambridge	England
\.


--
-- Data for Name: volunteer_experience; Type: TABLE DATA; Schema: s18group08; Owner: s18group08
--

COPY volunteer_experience (studentid, organization, start_date, end_date, total_hours, hours_per_week, healthcare_related) FROM stdin;
14260130	Red Cross	2017-10-07	2017-10-21	20	10	Yes
14253123	 Red Cross	2015-07-15	2015-07-16	5	5	Yes
14253123	 Dog Shelter	2018-01-15	\N	15	5	No
\.


--
-- Data for Name: work_experience; Type: TABLE DATA; Schema: s18group08; Owner: s18group08
--

COPY work_experience (studentid, employer, "position", start_date, end_date, hours_per_week, healthcare_related) FROM stdin;
14260130	Best Buy	Sales Consultant	2016-09-15	2017-08-05	25	No
17592749	 Barbara Park	Character	1992-11-29	2013-12-31	40	No
16326651	 Jimmy Johns	 Delivery Driver	2017-10-01	\N	30	No
14253123	 Starbucks	Clerk	2017-02-15	2017-05-15	29	No
14253123	 Mercy 	Blood Tech	2017-08-15	\N	40	Yes
\.


--
-- Name: academic_plan_pkey; Type: CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY academic_plan
    ADD CONSTRAINT academic_plan_pkey PRIMARY KEY (studentid, degree);


--
-- Name: event_pkey; Type: CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY event
    ADD CONSTRAINT event_pkey PRIMARY KEY (studentid, event_name, date_completed);


--
-- Name: extra_curricular_pkey; Type: CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY extra_curricular
    ADD CONSTRAINT extra_curricular_pkey PRIMARY KEY (studentid, organization, start_date);


--
-- Name: health_profession_school_pkey; Type: CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY health_profession_school
    ADD CONSTRAINT health_profession_school_pkey PRIMARY KEY (studentid, school_name);


--
-- Name: health_profession_test_pkey; Type: CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY health_profession_test
    ADD CONSTRAINT health_profession_test_pkey PRIMARY KEY (studentid, test_name, test_date);


--
-- Name: honors_info_pkey; Type: CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY honors_info
    ADD CONSTRAINT honors_info_pkey PRIMARY KEY (studentid);


--
-- Name: hs_test_pkey; Type: CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY hs_test
    ADD CONSTRAINT hs_test_pkey PRIMARY KEY (studentid, test_name);


--
-- Name: interview_join_pkey; Type: CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY interview_join
    ADD CONSTRAINT interview_join_pkey PRIMARY KEY (studentid, interviewerid);


--
-- Name: interview_pkey; Type: CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY interview
    ADD CONSTRAINT interview_pkey PRIMARY KEY (studentid);


--
-- Name: interviewer_pkey; Type: CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY interviewer
    ADD CONSTRAINT interviewer_pkey PRIMARY KEY (interviewerid);


--
-- Name: language_fluency_pkey; Type: CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY language_fluency
    ADD CONSTRAINT language_fluency_pkey PRIMARY KEY (studentid, language);


--
-- Name: leadership_position_pkey; Type: CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY leadership_position
    ADD CONSTRAINT leadership_position_pkey PRIMARY KEY (studentid, organization, "position", start_date);


--
-- Name: letter_join_pkey; Type: CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY letter_join
    ADD CONSTRAINT letter_join_pkey PRIMARY KEY (writerid, studentid);


--
-- Name: letter_writer_pkey; Type: CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY letter_writer
    ADD CONSTRAINT letter_writer_pkey PRIMARY KEY (writerid);


--
-- Name: login_pkey; Type: CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY login
    ADD CONSTRAINT login_pkey PRIMARY KEY (username);


--
-- Name: medopp_advisor_pkey; Type: CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY medopp_advisor
    ADD CONSTRAINT medopp_advisor_pkey PRIMARY KEY (advisorid);


--
-- Name: packet_information_pkey; Type: CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY packet_information
    ADD CONSTRAINT packet_information_pkey PRIMARY KEY (application_year);


--
-- Name: research_pkey; Type: CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY research
    ADD CONSTRAINT research_pkey PRIMARY KEY (studentid, lab_name);


--
-- Name: shadow_experience_pkey; Type: CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY shadow_experience
    ADD CONSTRAINT shadow_experience_pkey PRIMARY KEY (studentid, physician_last_name, physician_first_name);


--
-- Name: student_groups_pkey; Type: CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY student_groups
    ADD CONSTRAINT student_groups_pkey PRIMARY KEY (studentid, group_name);


--
-- Name: student_pkey; Type: CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY student
    ADD CONSTRAINT student_pkey PRIMARY KEY (studentid);


--
-- Name: study_abroad_pkey; Type: CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY study_abroad
    ADD CONSTRAINT study_abroad_pkey PRIMARY KEY (studentid, school_abroad, start_date);


--
-- Name: volunteer_experience_pkey; Type: CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY volunteer_experience
    ADD CONSTRAINT volunteer_experience_pkey PRIMARY KEY (studentid, organization, start_date);


--
-- Name: work_experience_pkey; Type: CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY work_experience
    ADD CONSTRAINT work_experience_pkey PRIMARY KEY (studentid, employer, "position", start_date);


--
-- Name: ethnicity_index; Type: INDEX; Schema: s18group08; Owner: s18group08
--

CREATE INDEX ethnicity_index ON s18group08.student USING btree (ethnic_group);


--
-- Name: academic_plan_studentid_fkey; Type: FK CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY academic_plan
    ADD CONSTRAINT academic_plan_studentid_fkey FOREIGN KEY (studentid) REFERENCES student(studentid) ON DELETE CASCADE;


--
-- Name: event_studentid_fkey; Type: FK CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY event
    ADD CONSTRAINT event_studentid_fkey FOREIGN KEY (studentid) REFERENCES student(studentid) ON DELETE CASCADE;


--
-- Name: extra_curricular_studentid_fkey; Type: FK CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY extra_curricular
    ADD CONSTRAINT extra_curricular_studentid_fkey FOREIGN KEY (studentid) REFERENCES student(studentid) ON DELETE CASCADE;


--
-- Name: health_profession_school_studentid_fkey; Type: FK CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY health_profession_school
    ADD CONSTRAINT health_profession_school_studentid_fkey FOREIGN KEY (studentid) REFERENCES student(studentid) ON DELETE CASCADE;


--
-- Name: health_profession_test_studentid_fkey; Type: FK CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY health_profession_test
    ADD CONSTRAINT health_profession_test_studentid_fkey FOREIGN KEY (studentid) REFERENCES student(studentid) ON DELETE CASCADE;


--
-- Name: honors_info_studentid_fkey; Type: FK CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY honors_info
    ADD CONSTRAINT honors_info_studentid_fkey FOREIGN KEY (studentid) REFERENCES student(studentid) ON DELETE CASCADE;


--
-- Name: hs_test_studentid_fkey; Type: FK CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY hs_test
    ADD CONSTRAINT hs_test_studentid_fkey FOREIGN KEY (studentid) REFERENCES student(studentid) ON DELETE CASCADE;


--
-- Name: interview_join_interviewerid_fkey; Type: FK CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY interview_join
    ADD CONSTRAINT interview_join_interviewerid_fkey FOREIGN KEY (interviewerid) REFERENCES interviewer(interviewerid) ON DELETE CASCADE;


--
-- Name: interview_join_studentid_fkey; Type: FK CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY interview_join
    ADD CONSTRAINT interview_join_studentid_fkey FOREIGN KEY (studentid) REFERENCES interview(studentid) ON DELETE CASCADE;


--
-- Name: interview_studentid_fkey; Type: FK CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY interview
    ADD CONSTRAINT interview_studentid_fkey FOREIGN KEY (studentid) REFERENCES student(studentid) ON DELETE CASCADE;


--
-- Name: language_fluency_studentid_fkey; Type: FK CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY language_fluency
    ADD CONSTRAINT language_fluency_studentid_fkey FOREIGN KEY (studentid) REFERENCES student(studentid) ON DELETE CASCADE;


--
-- Name: leadership_position_studentid_fkey; Type: FK CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY leadership_position
    ADD CONSTRAINT leadership_position_studentid_fkey FOREIGN KEY (studentid) REFERENCES student(studentid) ON DELETE CASCADE;


--
-- Name: letter_join_studentid_fkey; Type: FK CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY letter_join
    ADD CONSTRAINT letter_join_studentid_fkey FOREIGN KEY (studentid) REFERENCES student(studentid) ON DELETE CASCADE;


--
-- Name: letter_join_writerid_fkey; Type: FK CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY letter_join
    ADD CONSTRAINT letter_join_writerid_fkey FOREIGN KEY (writerid) REFERENCES letter_writer(writerid) ON DELETE CASCADE;


--
-- Name: research_studentid_fkey; Type: FK CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY research
    ADD CONSTRAINT research_studentid_fkey FOREIGN KEY (studentid) REFERENCES student(studentid) ON DELETE CASCADE;


--
-- Name: shadow_experience_studentid_fkey; Type: FK CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY shadow_experience
    ADD CONSTRAINT shadow_experience_studentid_fkey FOREIGN KEY (studentid) REFERENCES student(studentid) ON DELETE CASCADE;


--
-- Name: student_advisorid_fkey; Type: FK CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY student
    ADD CONSTRAINT student_advisorid_fkey FOREIGN KEY (advisorid) REFERENCES medopp_advisor(advisorid);


--
-- Name: student_groups_studentid_fkey; Type: FK CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY student_groups
    ADD CONSTRAINT student_groups_studentid_fkey FOREIGN KEY (studentid) REFERENCES student(studentid) ON DELETE CASCADE;


--
-- Name: study_abroad_studentid_fkey; Type: FK CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY study_abroad
    ADD CONSTRAINT study_abroad_studentid_fkey FOREIGN KEY (studentid) REFERENCES student(studentid) ON DELETE CASCADE;


--
-- Name: volunteer_experience_studentid_fkey; Type: FK CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY volunteer_experience
    ADD CONSTRAINT volunteer_experience_studentid_fkey FOREIGN KEY (studentid) REFERENCES student(studentid) ON DELETE CASCADE;


--
-- Name: work_experience_studentid_fkey; Type: FK CONSTRAINT; Schema: s18group08; Owner: s18group08
--

ALTER TABLE ONLY work_experience
    ADD CONSTRAINT work_experience_studentid_fkey FOREIGN KEY (studentid) REFERENCES student(studentid) ON DELETE CASCADE;


--
-- Name: s18group08; Type: ACL; Schema: -; Owner: s18group08
--

REVOKE ALL ON SCHEMA s18group08 FROM PUBLIC;
REVOKE ALL ON SCHEMA s18group08 FROM s18group08;
GRANT ALL ON SCHEMA s18group08 TO s18group08;
GRANT ALL ON SCHEMA s18group08 TO dsa_instructor;


--
-- PostgreSQL database dump complete
--

