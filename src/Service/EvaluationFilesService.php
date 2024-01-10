<?php

namespace App\Service;

use App\Entity\Evaluation;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class EvaluationFilesService
{
		private string $filesDirectory;

		public function __construct(ParameterBagInterface $params = null)
		{
			if (!is_null($params)) {
				$this->filesDirectory = $params->get('files_dir');
			}
		}

		public function getFilePath(Evaluation $evaluation, string $fileCategory, string $filename): ?string
		{
			$filePath = $this->filesDirectory . '/' . $evaluation->getId() . '/' . $fileCategory . '/' . $filename;
			return file_exists($filePath) ? $filePath : null;
		}

		public function getMimeType(string $filePath): string
		{
			$fileInfo = finfo_open(FILEINFO_MIME_TYPE);
			$mimeType = finfo_file($fileInfo, $filePath);
			finfo_close($fileInfo);
			return $mimeType;
		}

		public function getFileLocations(Evaluation $evaluation): array
		{
			return array(
				'course_syllabus' => $this->getCourseSyllabusLocation($evaluation),
				'course_document' => $this->getCourseDocumentLocation($evaluation),
				'lab_syllabus' => $this->getLabSyllabusLocation($evaluation),
				'lab_document' => $this->getLabDocumentLocation($evaluation),
				'attachments' => $this->getAttachmentsLocations($evaluation),
			);
		}

		public function saveSyllabus(Evaluation $eval, array $formData): void
		{
			if (!file_exists("../files/{$eval->getID()}")) {
				mkdir("../files/{$eval->getID()}", 0777, true);
			}
			if (!file_exists("../files/{$eval->getID()}/course_syllabus")) {
				mkdir("../files/{$eval->getID()}/course_syllabus", 0777, true);
			}
			$fileExtension = $formData['courseSyllabus']->getClientOriginalExtension();
			$timestamp = (new \DateTime())->format('YmdHis');
			$formData['courseSyllabus']->move(
				"../files/{$eval->getID()}/course_syllabus",
				$eval->getID() . '-syllabus-' . $timestamp . '.' . $fileExtension
			);
		}

		public function getCourseSyllabusLocation(Evaluation $evaluation): ?string
		{
			$directoryPath = $this->filesDirectory . '/' . $evaluation->getId() . '/course_syllabus';
			if (is_dir($directoryPath)) {
				$files = glob($directoryPath . '/*');
				if ($files !== false && count($files) > 0) {
					foreach ($files as $file) {
						return basename($file);
					}
				}
			}
			return null;
		}

		public function saveCourseDocument(Evaluation $eval, array $formData): void
		{
			if (!file_exists("../files/{$eval->getID()}")) {
				mkdir("../files/{$eval->getID()}", 0777, true);
			}
			if (!file_exists("../files/{$eval->getID()}/course_document")) {
				mkdir("../files/{$eval->getID()}/course_document", 0777, true);
			}
			$fileExtension = $formData['courseDocument']->getClientOriginalExtension();
			$timestamp = (new \DateTime())->format('YmdHis');
			$formData['courseDocument']->move(
				"../files/{$eval->getID()}/course_document",
				$eval->getID() . '-document-' . $timestamp . '.' . $fileExtension
			);
		}

		public function getCourseDocumentLocation(Evaluation $evaluation): ?string
		{
			$directoryPath = $this->filesDirectory . '/' . $evaluation->getId() . '/course_document';
			if (is_dir($directoryPath)) {
				$files = glob($directoryPath . '/*');
				if ($files !== false && count($files) > 0) {
					foreach ($files as $file) {
						return basename($file);
					}
				}
			}
			return null;
		}

		public function saveLabSyllabus(Evaluation $eval, array $formData): void
		{
			if (!file_exists("../files/{$eval->getID()}")) {
				mkdir("../files/{$eval->getID()}", 0777, true);
			}
			if (!file_exists("../files/{$eval->getID()}/lab_syllabus")) {
				mkdir("../files/{$eval->getID()}/lab_syllabus", 0777, true);
			}
			$fileExtension = $formData['labSyllabus']->getClientOriginalExtension();
			$timestamp = (new \DateTime())->format('YmdHis');
			$formData['labSyllabus']->move(
				"../files/{$eval->getID()}/lab_syllabus",
				$eval->getID() . '-lab-syllabus-' . $timestamp . '.' . $fileExtension
			);
		}

		public function getLabSyllabusLocation(Evaluation $evaluation): ?string
		{
			$directoryPath = $this->filesDirectory . '/' . $evaluation->getId() . '/lab_syllabus';
			if (is_dir($directoryPath)) {
				$files = glob($directoryPath . '/*');
				if ($files !== false && count($files) > 0) {
					foreach ($files as $file) {
						return basename($file);
					}
				}
			}
			return null;
		}

		public function saveLabDocument(Evaluation $eval, array $formData): void
		{
			if (!file_exists("../files/{$eval->getID()}")) {
				mkdir("../files/{$eval->getID()}", 0777, true);
			}
			if (!file_exists("../files/{$eval->getID()}/lab_document")) {
				mkdir("../files/{$eval->getID()}/lab_document", 0777, true);
			}
			$fileExtension = $formData['labDocument']->getClientOriginalExtension();
			$timestamp = (new \DateTime())->format('YmdHis');
			$formData['labDocument']->move(
				"../files/{$eval->getID()}/lab_document",
				$eval->getID() . '-lab-document-' . $timestamp . '.' . $fileExtension
			);
		}

		public function getLabDocumentLocation(Evaluation $evaluation): ?string
		{
			$directoryPath = $this->filesDirectory . '/' . $evaluation->getId() . '/lab_document';
			if (is_dir($directoryPath)) {
				$files = glob($directoryPath . '/*');
				if ($files !== false && count($files) > 0) {
					foreach ($files as $file) {
						return basename($file);
					}
				}
			}
			return null;
		}

		public function saveAttachment(Evaluation $eval, array $formData): void
		{
			if (!file_exists("../files/{$eval->getID()}")) {
				mkdir("../files/{$eval->getID()}", 0777, true);
			}
			if (!file_exists("../files/{$eval->getID()}/attachments")) {
				mkdir("../files/{$eval->getID()}/attachments", 0777, true);
			}
			$fileExtension = $formData['attachedFile']->getClientOriginalExtension();
			$timestamp = (new \DateTime())->format('YmdHis');
			$formData['attachedFile']->move(
				"../files/{$eval->getID()}/attachments",
				$eval->getID() . '-attachment-' . $timestamp . '.' . $fileExtension
			);
		}

		public function getAttachmentsLocations(Evaluation $evaluation): array
		{
			$attachments = array();
			$directoryPath = $this->filesDirectory . '/' . $evaluation->getId() . '/attachments';
			if (is_dir($directoryPath)) {
				$files = glob($directoryPath . '/*');
				if ($files !== false && count($files) > 0) {
					foreach ($files as $file) {
						$attachments[] = basename($file);
					}
				}
			}
			return $attachments;
		}
}