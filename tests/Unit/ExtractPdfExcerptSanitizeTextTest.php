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
}
