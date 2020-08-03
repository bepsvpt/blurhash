<?php

namespace Bepsvpt\Blurhash\Tests;

use Bepsvpt\Blurhash\BlurHash;
use PHPUnit\Framework\TestCase;

final class BlurHashTest extends TestCase
{
    public function testEncode(): void
    {
        $hash = new BlurHash;

        $this->assertSame(
            'LNTRWov}hKx]wJj[eTjZheeneTgh',
            $hash->encode(__DIR__.'/images/1.png')
        );

        $this->assertSame(
            'LvTQiWrXe9rr%Miwenj[l.e9ene.',
            $hash->encode(__DIR__.'/images/2.png')
        );
    }

    public function testEncodeDifferentComponents(): void
    {
        $this->assertSame(
            'LCTQ}j*|f+-V+HkWY+i_l.dpgNg$',
            (new BlurHash)->encode(__DIR__.'/images/3.png')
        );

        $this->assertSame(
            '00TQ}j',
            (new BlurHash(1, 1))->encode(__DIR__.'/images/3.png')
        );

        $hash = (new BlurHash)
            ->setComponentX(1)
            ->setComponentY(1);

        $this->assertSame(
            '00TQ}j',
            $hash->encode(__DIR__.'/images/3.png')
        );

        $this->assertSame(
            '|CTQ}j*|f+-Vg$*|dC-Vd=+HkWY+i_g3kqeTi_gNl.dpgNg$fkg3f+gNlT.8h}e.ich0icg3iwd=g$enghghgNeng$g3en.SjFe.i_eSkqghk=fklneng3ghf+ghgNe9ZN-;kCe.i_h0lngNk=g3enghf6gNe9eTgNg$g3',
            (new BlurHash(9, 9))->encode(__DIR__.'/images/3.png')
        );
    }

    public function testEncodeDifferentImageWidth(): void
    {
        $hash = new BlurHash;

        $this->assertSame(
            'LxTP*ctRe9t7*Jj[f6kCh0enene.',
            $hash->encode(__DIR__.'/images/4.png')
        );

        $hash->setResizedImageMaxWidth(48);

        $this->assertSame(
            'LyTPVXtRe.t7.mkCfkkWlTenene.',
            $hash->encode(__DIR__.'/images/4.png')
        );

        $hash->setResizedImageMaxWidth(32);

        $this->assertSame(
            'L*TOz#xaeTxu?^kCf6kWhKe.enf6',
            $hash->encode(__DIR__.'/images/4.png')
        );

        $hash->setResizedImageMaxWidth(96);

        $this->assertSame(
            'LrTQ4BozeTo}*Jfkf6f+lTenemen',
            $hash->encode(__DIR__.'/images/4.png')
        );
    }
}
