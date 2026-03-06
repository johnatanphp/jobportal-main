<?php
//require_once APPPATH . 'libraries/phpword/vendor/autoload.php';

use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Style\Language;
use PhpOffice\PhpWord\PhpWord;

class Cv_word {

	private $phpword = null;

	private $data = array();

	public function __construct()
	{
		$this->phpword = new PhpWord();

		$language = new Language(Language::ES_ES);	
		$this->phpword->getSettings()->setThemeFontLang($language);

		$this->phpword->setDefaultFontName('Arial');
		$this->phpword->setDefaultFontSize(12); 
	}

	public function build_cv_word($data = array())
	{
		$this->data = $data;

		$section = $this->phpword->addSection();

		$this->build_header($section);

		$this->build_answers_applicant($section);

		$this->build_professional_resume($section);

		$this->build_experience($section);

		$this->build_qualification($section);

		$this->build_other_studies($section);

		$this->build_additional_info($section);
	}

	private function build_header($section)
	{
		$jobseeker = $this->data['row'];

		//Set properties document
		$properties = $this->phpword->getDocInfo();
		$properties->setCreator(SITE_NAME . ' - Corporativo Overall');
		$properties->setCompany('Corporativo Overall');
		$properties->setTitle($this->escape_str('CV - ' . $jobseeker->first_name));
		$properties->setDescription($this->escape_str('CV - ' . $jobseeker->first_name));
		$properties->setSubject($this->escape_str('CV - ' . $jobseeker->first_name));
		$properties->setCreated(time());
		$properties->setModified(time());

		//Add Logo coorporativo overall
		$section->addImage(FCPATH . 'public/images/overall_blue.png', array('width'=> 160, 'height'=> 35));

		$section->addImage(
	        img_pic_candidate($jobseeker->photo),
	        array(
				'width' => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(2.3),
				'height' => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(2.6),
				'wrappingStyle' => 'square',
				'positioning' => 'absolute',
				'posHorizontal'    => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_RIGHT,
				'posHorizontalRel' => 'margin',
				'posVerticalRel' => 'line',
				'marginTop' => 100,
	        )
   	 	);

		$font_style = array(
			'size' => 18,
			'bold' => true,
			'color' => '333333'
		);

		$textrun = $section->addTextRun();
		$textrun->addText($this->escape_str($jobseeker->first_name), $font_style);

		$font_style = array(
			'color' => '333333'
		);

		$properties_img = array(
			'width' => 14,
			'height' => 14
		);

		//Add info jobseekers
		$textrun = $section->addTextRun();
		$textrun->addImage(FCPATH  . 'public/images/png/008-email.png', $properties_img);
		$textrun->addText($this->escape_str(' ' . $jobseeker->email), $font_style);

		 if (!empty($jobseeker->country) && !empty($jobseeker->city)) {
			$textrun = $section->addTextRun();
			$textrun->addImage(FCPATH  . 'public/images/png/002-tool-1.png', $properties_img);
			$textrun->addText($this->escape_str(' ' . $jobseeker->city . ' - ' . country_text($jobseeker->country)), $font_style);
		}

		if (!empty($jobseeker->dob) && !empty($jobseeker->gender)) {
			$textrun = $section->addTextRun();
			$textrun->addImage(FCPATH  . 'public/images/png/004-profile.png', $properties_img);
			$textrun->addText(' ' . _date_locale_format(strtotime($jobseeker->dob), 'dd/MM/y') . ' (' . get_age($jobseeker->dob) . ' años) - ' . gender_text($jobseeker->gender), $font_style);
		}

		if (!empty($jobseeker->document_type) && !empty($jobseeker->document_number)) {
			$textrun = $section->addTextRun();
			$textrun->addImage(FCPATH  . 'public/images/png/011-people-1.png', $properties_img);
			$textrun->addText(' ' . document_type_text($jobseeker->document_type) . ': ' . $jobseeker->document_number, $font_style);
		}

		if (!empty($jobseeker->mobile)) {
			$textrun = $section->addTextRun();
			$textrun->addImage(FCPATH  . 'public/images/png/mobile.png', $properties_img);
			$textrun->addText($this->escape_str(' ' . $jobseeker->mobile), $font_style);
			$textrun->addText('      ');
		}
		
		if (!empty($jobseeker->home_phone)) {
			$textrun->addImage(FCPATH  . 'public/images/png/homepage.png', $properties_img);
			$textrun->addText($this->escape_str(' ' . $jobseeker->home_phone), $font_style);
		}

		$section->addTextBreak();
	}
	
	private function build_answers_applicant($section)
	{
		$result_answers_applicant = isset($this->data['result_answers_applicant']) ? $this->data['result_answers_applicant'] : array();

		if (empty($result_answers_applicant) || 
			!isset($this->data['show_questions']) || 
			$this->data['show_questions'] != 'yes') {
			return;
		}
		
		$this->section_title($section, 'Preguntas del empleo');

		$font_style = array(
			'bold' => true,
			'color' => '333333',
		);

		$font_style1 = array(
			'color' => '333333',
		);

		$i = 0;

		foreach ($result_answers_applicant as $row_answer) {
		
			$textrun = $section->addTextRun();
			$textrun->addText($this->escape_str((++$i) . ') ' . $row_answer->question), $font_style);
		
			$answer = '';
			$answer_data = get_answers_to_question($row_answer->question_ID, $row_answer->applied_ID);

			if ($row_answer->type_question != 'checkbox' ||
				$row_answer->type_question != 'multiple_choice_grid') {

				$answer = isset($answer_data[0]->answer_value) ? $this->escape_str('    ' . $answer_data[0]->answer_value) : '';	
			}

			if ($row_answer->type_question == 'checkbox') {
				$answer = '     ' . join(', ', array_map(function($item) {
					return $this->escape_str($item->answer_value);
				}, $answer_data));
			}

			if ($row_answer->type_question == 'multiple_choice_grid') {
				$answer = join('<w:br/>', array_map(function($item) {
					return $this->escape_str('     ' . $item->answer_value_row . ' - ' . $item->answer_value_column);
				}, $answer_data));
			}

			$textrun = $section->addTextRun();

			$textrun->addText(!empty($answer) ?  $answer : 'Sin respuesta', $font_style1);			
		}

		$section->addTextBreak();
	}

	private function build_professional_resume($section)
	{
		$row_additional = $this->data['row_additional'];

		if (empty(trim($row_additional->summary))) {
			return;
		}

		$this->section_title($section, 'Resumen profesional');

		$font_style = array(
			'color' => '333333'
		);
		
		$textrun = $section->addTextRun();
		$textrun->addText($this->escape_str($row_additional->summary), $font_style);
		$section->addTextBreak();
	}

	private function build_experience($section)
	{
		$result_experience = $this->data['result_experience'];

		if (empty($result_experience)) {
			return;
		}

		$this->section_title($section, 'Experiencia');

		$table = $section->addTable();

		foreach ($result_experience as $row_experience) {

			$table->addRow();

			$start_date = ucwords(_date_locale_format(strtotime($row_experience->start_date), 'MMM y'));
			$end_date = ($row_experience->end_date != null || $row_experience->end_date =! '0000-00-00') ? ucwords(_date_locale_format(strtotime($row_experience->end_date), 'MMM y')) : 'Presente';

			$table->addCell(2700)->addText($start_date . ' - ' . $end_date, 
				array(
					'color' => '555555',
					'italic' => true
				)
			);

			$cell = $table->addCell(7300);
			$textrun = $cell->addTextRun();
			$textrun->addText($this->escape_str($row_experience->job_title), 
				array(
					'color' => '333333',
					'bold' => true
				)
			);

			$textrun = $cell->addTextRun();
			$textrun->addText($this->escape_str($row_experience->company_name . ' - ' . $row_experience->country),
				array(
					'color' => '333333'
				)
			);

			$textrun = $cell->addTextRun();
			$textrun->addText($this->escape_str($row_experience->description), 
				array(
				'color' => '555555'
				)
			);
		}
	
		$section->addTextBreak();
	}

	private function build_qualification($section)
	{
		$result_qualification = $this->data['result_qualification'];

		if (empty($result_qualification)) {
			return;
		}

		$this->section_title($section, 'Educación');

		$font_style1 = array(
			'color' => '555555',
			'italic' => true
		);

		$font_style2 = array(
			'color' => '333333',
			'bold' => true
		);

		$font_style3 = array(
			'color' => '333333'
		);

		$table = $section->addTable();

		foreach ($result_qualification as $row_qualification) {
			
			$table->addRow();

			$start_date = ucwords(_date_locale_format(strtotime($row_qualification->start_date), 'MMM y'));
			$end_date = ($row_qualification->end_date != null || $row_qualification->end_date =! '0000-00-00') ? ucwords(_date_locale_format(strtotime($row_qualification->end_date), 'MMM y')) : 'Presente';
			
			$table->addCell(2700)->addText($start_date . ' - ' . $end_date, $font_style1);
			$cell = $table->addCell(7300);
			$textrun = $cell->addTextRun();
			$textrun->addText($this->escape_str($row_qualification->institude), $font_style2);
			$textrun = $cell->addTextRun();
			$textrun->addText($this->escape_str($row_qualification->degree_title . ' - '. $row_qualification->major), $font_style3);
		}
		
		$section->addTextBreak();
	}

	private function build_other_studies($section)
	{
		$result_other_studies = $this->data['result_other_studies'];

		if (empty($result_other_studies)) {
			return;
		}

		$this->section_title($section, 'Otros estudios');

		$font_style1 = array(
			'color' => '555555',
			'italic' => true
		);

		$font_style2 = array(
			'color' => '333333',
			'bold' => true
		);

		$font_style3 = array(
			'color' => '333333'
		);

		$table = $section->addTable();

		foreach ($result_other_studies as $row_other_studies) {
			
			$table->addRow();

			$start_date = ucwords(_date_locale_format(strtotime($row_other_studies->start_date), "MMM y"));
			$end_date = $row_other_studies->end_date != null && $row_other_studies->end_date != '0000-00-00' ? ucwords(_date_locale_format(strtotime($row_other_studies->end_date), "MMM y")) : "Presente"; 

			$table->addCell(2700)->addText($start_date . ' - ' . $end_date, $font_style1);
			$cell = $table->addCell(7300);

			$textrun = $cell->addTextRun();
			$textrun->addText($this->escape_str($row_other_studies->institute), $font_style2);
			$textrun = $cell->addTextRun();
			$textrun->addText($this->escape_str($row_other_studies->name . ' - '. $row_other_studies->type), $font_style3);
		}
			
		$section->addTextBreak();
	}

	private function build_additional_info($section)
	{
		$row_additional = $this->data['row_additional'];

		if (empty($row_additional->interest) && 
			empty($row_additional->description) && 
		    empty($row_additional->awards) &&
			empty($row_additional->salary_min) &&
			empty($row_additional->salary_max)
			
		) {
			return;
		}
		
		$font_style1 = array(
			'bold' => true,
			'color' => '333333'
		);

		$font_style2 = array(
			'color' => '333333'
		);

		$this->section_title($section, 'Información adicional');

		$table = $section->addTable();

		$table->addRow();
		$table->addCell(2600)->addText('Pretensión salarial:', $font_style1);
		$cell = $table->addCell(7400);
		$cell->addText(($row_additional->salary_currency) ? $row_additional->salary_currency . ' ' . $row_additional->salary_min . ' - ' . $row_additional->salary_max : ' - ', $font_style2);

		$table->addRow();
		$table->addCell(2600)->addText('Intereses:', $font_style1);
		$cell = $table->addCell(7400);
		$cell->addText(($row_additional->interest) ? $this->escape_str($row_additional->interest) : ' - ', $font_style2);

		$table->addRow();
		$table->addCell(2600)->addText('Objetivos:', $font_style1);
		$cell = $table->addCell(7400);
		$cell->addText(($row_additional->description) ? $this->escape_str($row_additional->description) : ' - ', $font_style2);

		$table->addRow();
		$table->addCell(2600)->addText('Logros / Premios:', $font_style1);
		$cell = $table->addCell(7400);
		$cell->addText(($row_additional->awards) ? $this->escape_str($row_additional->awards) : ' - ', $font_style2);
		
		$section->addTextBreak();
	}

	private function section_title($section, $title)
	{
		$font_style = array(
			'size' => 14,
			'bold' => true,
			'color' => '333333',
		);

		$textrun = $section->addTextRun();
		$textrun->addText($title, $font_style);

		$section->addShape(
			'line',
			array(
				'points'  => '1,1 620,1',
				'outline' => 
				array(
					'color' => '#444444',
					'weight' => 2,
				),
			)
		);
	}

	public function download($filename = 'document-word.docx')
	{
		header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessing‌​ml.document');
		header('Content-Disposition: attachment; filename=' . $filename);

		$h2d_file_uri = tempnam('', 'htd');
		$obj_writer = \PhpOffice\PhpWord\IOFactory::createWriter($this->phpword, 'Word2007');
		$obj_writer->save('php://output');
	}

	public function save($target_file)
	{
		$this->phpword->save($target_file, 'Word2007');
	}

	/**
	 * Escape string for XML
	 *
	 * @param	string	$s
	 * @param	bool	$escapeNewline
	 * @return	string 
	 */
	
	private function escape_str($s, $escapeNewline = false)
	{
		$w = '';
		$Last = 0;
		$l = strlen($s);
		$i = 0;

		while ($i < $l) {
		    $r = mb_substr(substr($s, $i), 0, 1);
		    $Width = strlen($r);
		    $i += $Width;
		    switch ($r) {
		        case '"':
		            $esc = '&#34;';
		            break;
		        case "'":
		            $esc = '&#39;';
		            break;
		        case '&':
		            $esc = '&amp;';
		            break;
		        case '<':
		            $esc = '&lt;';
		            break;
		        case '>':
		            $esc = '&gt;';
		            break;
		        case "\t":
		            $esc = '&#x9;';
		            break;
		       
		        case "\n":
		            if (!$escapeNewline) {
		                continue 2;
		            }
		            $esc = '&#xA;';
		            break;
		        /*
		        case "\r":
		            $esc = '&#xD;';
		            break;
		        */
		        default:
		            if (!$this->isInCharacterRange($this->mb_ord($r)) || ($this->mb_ord($r) === 0xFFFD && $Width === 1)) {
		                $esc = "\u{FFFD}";
		                break;
		            }

		            continue 2;
		    }
		    $w .= substr($s, $Last, $i - $Last - $Width) . $esc;
		    $Last = $i;
		}
		$w .= substr($s, $Last);
		return $w;
	}

	private function mb_ord($char, $encoding = 'UTF-8')
	{
		if ($encoding === 'UCS-4BE') {
		    list(, $ord) = strlen($char) === 4 ? @unpack('N', $char) : @unpack('n', $char);
		    return $ord;
		} else {
		    return mb_ord(mb_convert_encoding($char, 'UCS-4BE', $encoding), 'UCS-4BE');
		}
	}

	private function isInCharacterRange($r)
	{
		return 
			$r == 0x09 ||
			$r == 0x0A ||
			$r == 0x0D ||
			$r >= 0x20 && $r <= 0xDF77 ||
			$r >= 0xE000 && $r <= 0xFFFD ||
			$r >= 0x10000 && $r <= 0x10FFFF;
	}
}
