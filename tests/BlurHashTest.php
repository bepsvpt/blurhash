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
            match (PHP_OS_FAMILY) {
                'Darwin' => 'LNTa[Gv}hKx]wJj[eTjZheeneTgh',
                'Windows' => 'LNTa[Gv}hKx]wJj[eTjZheeneTgh',
                default => 'LNTRThv#lnx]wJj[eTjFl.eneTgh',
            },
            $hash->encode(__DIR__ . '/images/1.png')
        );

        $this->assertSame(
            match (PHP_OS_FAMILY) {
                'Darwin' => 'LvTa3}rXe9rr%Miwenj[hee9ene.',
                'Windows' => 'LvTa3}rXe9rr%Miwenj[hee9ene.',
                default => 'LRP;cSvMz;#mbb#-w{oz4S+bv#jF',
            },
            $hash->encode(__DIR__ . '/images/2.png')
        );
    }

    public function testEncodeDifferentComponents(): void
    {
        $this->assertSame(
            match (PHP_OS_FAMILY) {
                'Darwin' => 'LCTafB*|f+-V+HkWdCi_hedpgNg$',
                'Windows' => 'LCTafB*|f+-V+HkWdCi_hedpgNg$',
                default => 'L9OxXq|HkW#-00tl:6RP7|*|pIT{',
            },
            (new BlurHash)->encode(__DIR__ . '/images/3.png')
        );

        $this->assertSame(
            match (PHP_OS_FAMILY) {
                'Darwin' => '00TafB',
                'Windows' => '00TafB',
                default => '00OxXq',
            },
            (new BlurHash(1, 1))->encode(__DIR__ . '/images/3.png')
        );

        $this->assertSame(
            match (PHP_OS_FAMILY) {
                'Darwin' => '|CTafB*|f+-Vg$*|dC-Vd=+HkWdCi_g3kqeTi_gNhedpgNg$fkg3f+gNh0.8h}e.ich0icg3iwd=g$enghghgNeng$g3en.SjFe.i_eSkqghk=fkhKeng3ghf+ghgNe9dq-;kCe.i_h0lngNk=g3enghf6gNe9eTgNg$g3',
                'Windows' => '|CTafB*|f+-Vg$*|dC-Vd=+HkWdCi_g3kqeTi_gNhedpgNg$fkg3f+gNh0.8h}e.ich0icg3iwd=g$enghghgNeng$g3en.SjFe.i_eSkqghk=fkhKeng3ghf+ghgNe9dq-;kCe.i_h0lngNk=g3enghf6gNe9eTgNg$g3',
                default => '|9OxXq|HkW#-F_:Ql:-U+^00tl:6RPNGpIv~R5Xm7|*|pIT{M{4.W;k=Gt00*|RPQ-L1#8NGrq#8BTaJt,cYI:xaKiIoiw00xaRPR5VEELO?PAWBG[xGo}O?tRFwS~#S:+00xuRPVYL1C6J7FJIo^+pcofOXQ-rXJRPAx]',
            },
            (new BlurHash(9, 9))->encode(__DIR__ . '/images/3.png')
        );
    }

    public function testEncodeDifferentImageWidth(): void
    {
        $hash = new BlurHash;

        $this->assertSame(
            match (PHP_OS_FAMILY) {
                'Darwin' => 'LxTZP4tRe9t7*Jj[f6kCh0enene.',
                'Windows' => 'LxTZP4tRe9t7*Jj[f6kCh0enene.',
                default => 'LxTPuEtRe9t7*Jj[f6kCh0enene.',
            },
            $hash->encode(__DIR__ . '/images/4.png')
        );

        $hash->setResizedImageMaxWidth(48);

        $this->assertSame(
            match (PHP_OS_FAMILY) {
                'Darwin' => 'LyTY?~tRe.t7.mkCfkkWh0enene.',
                'Windows' => 'LyTY?~tRe.t7.mkCfkkWh0enene.',
                default => 'LUQr@dx]nOoLY*ozkCNbC+rrrXn%',
            },
            $hash->encode(__DIR__ . '/images/4.png')
        );

        $hash->setResizedImageMaxWidth(32);

        $this->assertSame(
            match (PHP_OS_FAMILY) {
                'Darwin' => 'L*TYLSxaeTxu?^kCf6kWhKe.enf6',
                'Windows' => 'L*TYLSxaeTxu?^kCf6kWhKe.enf6',
                default => 'L$TOqdxue9xu?^kCe.kWhKe.eTfk',
            },
            $hash->encode(__DIR__ . '/images/4.png')
        );

        $hash->setResizedImageMaxWidth(96);

        $this->assertSame(
            match (PHP_OS_FAMILY) {
                'Darwin' => 'LrTZkyozeTo}*Jfkf6f+h0enemen',
                'Windows' => 'LrTZkyozeTo}*Jfkf6f+h0enemen',
                default => 'LVQQ5jXSrXbvLzWVV@bbCQrrrXni',
            },
            $hash->encode(__DIR__ . '/images/4.png')
        );
    }
}
