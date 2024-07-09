<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;

final class FileInputTest extends TestCase
{
    // Vérifie sur l'input de l'image si l'attribut "required" est présent
    public function testFileInputIsRequired(): void
    {
        // Chemin vers le formulaire
        $htmlFilePath = '/var/www/html/App/Views/Product/Add.html';
        // Charge le contenu HTML du fichier
        $htmlContent = file_get_contents($htmlFilePath);
        // Parcoure le HTML fourni
        $crawler = new Crawler($htmlContent);
        // Sélectionne l'input dans le form
        $inputFile = $crawler->filter('input[type="file"]')->first();
        // Vérifie si 'required' est présent sur l'input file
        $requiredAttribute = $inputFile->attr('required');
        // Assertion : vérifie que l'attribut 'required' est présent
        $this->assertNotNull($requiredAttribute, 'The "required" attribute should be present on the file input');
        echo "L'attribut 'required' est présent sur le champ de l'image.\n";
    }
}
