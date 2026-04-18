<?php

namespace Tests\Unit;

use App\Jobs\ExtractPdfExcerpt;
use PHPUnit\Framework\TestCase;

class ExtractPdfExcerptSanitizeTextTest extends TestCase
{
    public function test_it_removes_special_symbols_greek_letters_and_arrows(): void
    {
        $input = 'Texte α β γ ∫ x → y ⇨ z @#$ 123';

        $this->assertSame('Texte x y z 123', ExtractPdfExcerpt::sanitizeText($input));
    }

    public function test_it_keeps_latin_letters_with_accents_numbers_and_normalized_spaces(): void
    {
        $input = "  Révision   été\t2026\nchapitre 4  ";

        $this->assertSame('Révision été 2026 chapitre 4', ExtractPdfExcerpt::sanitizeText($input));
    }

    public function test_it_parses_flat_toc_from_pdftk_dump_data(): void
    {
        $dumpData = <<<'TXT'
BookmarkBegin
BookmarkTitle: Applications lin&eacute;aires
BookmarkLevel: 1
BookmarkPageNumber: 1
BookmarkBegin
BookmarkTitle: Images et noyaux
BookmarkLevel: 2
BookmarkPageNumber: 3
PageMediaBegin
PageMediaNumber: 1
TXT;

        $this->assertSame([
            [
                'title' => 'Applications linéaires',
                'level' => 1,
                'page' => 1,
            ],
            [
                'title' => 'Images et noyaux',
                'level' => 2,
                'page' => 3,
            ],
        ], ExtractPdfExcerpt::parseTocDumpData($dumpData));
    }
}
