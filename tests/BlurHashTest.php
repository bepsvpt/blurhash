<?php

namespace Bepsvpt\Blurhash\Tests;

use Bepsvpt\Blurhash\BlurHash;
use PHPUnit\Framework\TestCase;

class BlurHashTest extends TestCase
{
    public function testEncode(): void
    {
        $hash = new BlurHash;

        $this->assertSame(
            'LNTa[Gv}hKx]wJj[eTjZheeneTgh',
            $hash->encode(__DIR__ . '/images/1.png')
        );

        $this->assertSame(
            'LvTa3}rXe9rr%Miwenj[hee9ene.',
            $hash->encode(__DIR__ . '/images/2.png')
        );
    }

    public function testEncodeDifferentComponents(): void
    {
        $this->assertSame(
            'LCTafB*|f+-V+HkWdCi_hedpgNg$',
            (new BlurHash)->encode(__DIR__ . '/images/3.png')
        );

        $this->assertSame(
            '00TafB',
            (new BlurHash(1, 1))->encode(__DIR__ . '/images/3.png')
        );

        $this->assertSame(
            '|CTafB*|f+-Vg$*|dC-Vd=+HkWdCi_g3kqeTi_gNhedpgNg$fkg3f+gNh0.8h}e.ich0icg3iwd=g$enghghgNeng$g3en.SjFe.i_eSkqghk=fkhKeng3ghf+ghgNe9dq-;kCe.i_h0lngNk=g3enghf6gNe9eTgNg$g3',
            (new BlurHash(9, 9))->encode(__DIR__ . '/images/3.png')
        );
    }

    public function testEncodeDifferentImageWidth(): void
    {
        $hash = new BlurHash;

        $this->assertSame(
            'LxTZP4tRe9t7*Jj[f6kCh0enene.',
            $hash->encode(__DIR__ . '/images/4.png')
        );

        $hash->setResizedImageMaxWidth(48);

        $this->assertSame(
            'LyTY?~tRe.t7.mkCfkkWh0enene.',
            $hash->encode(__DIR__ . '/images/4.png')
        );

        $hash->setResizedImageMaxWidth(32);

        $this->assertSame(
            'L*TYLSxaeTxu?^kCf6kWhKe.enf6',
            $hash->encode(__DIR__ . '/images/4.png')
        );

        $hash->setResizedImageMaxWidth(96);

        $this->assertSame(
            'LrTZkyozeTo}*Jfkf6f+h0enemen',
            $hash->encode(__DIR__ . '/images/4.png')
        );
    }
}
