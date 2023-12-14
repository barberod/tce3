<?php

namespace App\Service;

use App\Entity\Evaluation;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class EvaluationFilesService
{
		private string $filesDirectory;

		public function __construct(ParameterBagInterface $params)
		{
				$this->filesDirectory = $params->get('files_dir');
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

		public function getCourseSyllabusLocation(Evaluation $evaluation): ?string
		{
				$directoryPath = $this->filesDirectory . '/' . $evaluation->getId() . '/course-syllabus';
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

		public function getCourseDocumentLocation(Evaluation $evaluation): ?string
		{
				$directoryPath = $this->filesDirectory . '/' . $evaluation->getId() . '/course-document';
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

		public function getLabSyllabusLocation(Evaluation $evaluation): ?string
		{
				$directoryPath = $this->filesDirectory . '/' . $evaluation->getId() . '/lab-syllabus';
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

		public function getLabDocumentLocation(Evaluation $evaluation): ?string
		{
				$directoryPath = $this->filesDirectory . '/' . $evaluation->getId() . '/lab-document';
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