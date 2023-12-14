<?php

namespace App\Service;

use App\Entity\Evaluation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class EvaluationFilesService
{
		private $filesDirectory;

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
					'courseSyllabus' => $this->getCourseSyllabusLocation($evaluation),
					'courseDocument' => $this->getCourseDocumentLocation($evaluation),
					'labSyllabus' => $this->getLabSyllabusLocation($evaluation),
					'labDocument' => $this->getLabDocumentLocation($evaluation),
					'attachments' => $this->getAttachmentsLocations($evaluation),
				);
		}

		public function getCourseSyllabusLocation(Evaluation $evaluation): ?string
		{
				return 'hi';
		}

		public function getCourseDocumentLocation(Evaluation $evaluation): ?string
		{
				return 'hi';
		}

		public function getLabSyllabusLocation(Evaluation $evaluation): ?string
		{
				return 'hi';
		}

		public function getLabDocumentLocation(Evaluation $evaluation): ?string
		{
				return 'hi';
		}

		public function getAttachmentsLocations(Evaluation $evaluation): array
		{
				return array();
		}
}