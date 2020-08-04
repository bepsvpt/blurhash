<?php

namespace Bepsvpt\Blurhash\Tests;

use Bepsvpt\Blurhash\BlurHash;
use PHPUnit\Framework\TestCase;

final class BlurHashTest extends TestCase
{
    /**
     * Different GD library will get different
     * rgb value for same image pixel. Thus,
     * the encoding will be different.
     *
     * @var bool
     */
    protected $isOldGdLibrary = false;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->isOldGdLibrary = isOldGdLibrary();
    }

    public function testEncode(): void
    {
        $hash = new BlurHash;

        $this->assertSame(
            $this->isOldGdLibrary ? 'LNTRWov}hKx]wJj[eTjZheeneTgh' : 'LNTRThv#lnx]wJj[eTjFl.eneTgh',
            $hash->encode(__DIR__.'/images/1.png')
        );

        $this->assertSame(
            $this->isOldGdLibrary ? 'LvTQiWrXe9rr%Miwenj[l.e9ene.' : 'LRP;cSvMz;#mbb#-w{oz4S+bv#jF',
            $hash->encode(__DIR__.'/images/2.png')
        );
    }

    public function testEncodeDifferentComponents(): void
    {
        $this->assertSame(
            $this->isOldGdLibrary ? 'LCTQ}j*|f+-V+HkWY+i_l.dpgNg$' : 'L9OxXq|HkW#-00tl:6RP7|*|pIT{',
            (new BlurHash)->encode(__DIR__.'/images/3.png')
        );

        $this->assertSame(
            $this->isOldGdLibrary ? '00TQ}j' : '00OxXq',
            (new BlurHash(1, 1))->encode(__DIR__.'/images/3.png')
        );

        $this->assertSame(
            $this->isOldGdLibrary
                ? '|CTQ}j*|f+-Vg$*|dC-Vd=+HkWY+i_g3kqeTi_gNl.dpgNg$fkg3f+gNlT.8h}e.ich0icg3iwd=g$enghghgNeng$g3en.SjFe.i_eSkqghk=fklneng3ghf+ghgNe9ZN-;kCe.i_h0lngNk=g3enghf6gNe9eTgNg$g3'
                : '|9OxXq|HkW#-F_:Ql:-U+^00tl:6RPNGpIv~R5Xm7|*|pIT{M{4.W;k=Gt00*|RPQ-L1#8NGrq#8BTaJt,cYI:xaKiIoiw00xaRPR5VEELO?PAWBG[xGo}O?tRFwS~#S:+00xuRPVYL1C6J7FJIo^+pcofOXQ-rXJRPAx]',
            (new BlurHash(9, 9))->encode(__DIR__.'/images/3.png')
        );
    }

    public function testEncodeDifferentImageWidth(): void
    {
        $hash = new BlurHash;

        $this->assertSame(
            $this->isOldGdLibrary ? 'LxTP*ctRe9t7*Jj[f6kCh0enene.' : 'LxTPuEtRe9t7*Jj[f6kCh0enene.',
            $hash->encode(__DIR__.'/images/4.png')
        );

        $hash->setResizedImageMaxWidth(48);

        $this->assertSame(
            $this->isOldGdLibrary ? 'LyTPVXtRe.t7.mkCfkkWlTenene.' : 'LUQr@dx]nOoLY*ozkCNbC+rrrXn%',
            $hash->encode(__DIR__.'/images/4.png')
        );

        $hash->setResizedImageMaxWidth(32);

        $this->assertSame(
            $this->isOldGdLibrary ? 'L*TOz#xaeTxu?^kCf6kWhKe.enf6' : 'L$TOqdxue9xu?^kCe.kWhKe.eTfk',
            $hash->encode(__DIR__.'/images/4.png')
        );

        $hash->setResizedImageMaxWidth(96);

        $this->assertSame(
            $this->isOldGdLibrary ? 'LrTQ4BozeTo}*Jfkf6f+lTenemen' : 'LVQQ5jXSrXbvLzWVV@bbCQrrrXni',
            $hash->encode(__DIR__.'/images/4.png')
        );
    }
}
