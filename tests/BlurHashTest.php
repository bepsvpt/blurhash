<?php

namespace Bepsvpt\Blurhash\Tests;

use Bepsvpt\Blurhash\BlurHash;
use PHPUnit\Framework\TestCase;

class BlurHashTest extends TestCase
{
    public function testEncode(): void
    {
        $hash = new BlurHash();

        $this->assertSame(
            match (PHP_OS_FAMILY) {
                'Darwin', 'Windows' => 'LNTRThv}hKx]wJj[eTjZheeneTgh',
                default => 'LNTRQZv#lnx]wJj[eTjFl.eneTgh',
            },
            $hash->encode(__DIR__ . '/images/1.png')
        );

        $this->assertSame(
            match (PHP_OS_FAMILY) {
                'Darwin', 'Windows' => 'LvTQfPrXe9rr%Miwenj[hee9ene.',
                default => 'LRP;cSvMz;#mbb#-w{oz4S+bv#jF',
            },
            $hash->encode(__DIR__ . '/images/2.png')
        );
    }

    public function testEncodeDifferentComponents(): void
    {
        $this->assertSame(
            match (PHP_OS_FAMILY) {
                'Darwin', 'Windows' => 'LCTQ_c*|f+-V+HkWdCi_hedpgNg$',
                default => 'L9On@3|HkW#-00tl:6RP7|*|pIT{',
            },
            (new BlurHash())->encode(__DIR__ . '/images/3.png')
        );

        $this->assertSame(
            match (PHP_OS_FAMILY) {
                'Darwin', 'Windows' => '00TQ_c',
                default => '00On@3',
            },
            (new BlurHash(1, 1))->encode(__DIR__ . '/images/3.png')
        );

        $this->assertSame(
            match (PHP_OS_FAMILY) {
                'Darwin', 'Windows' => '|CTQ_c*|f+-Vg$*|dC-Vd=+HkWdCi_g3kqeTi_gNhedpgNg$fkg3f+gNh0.8h}e.ich0icg3iwd=g$enghghgNeng$g3en.SjFe.i_eSkqghk=fkhKeng3ghf+ghgNe9dq-;kCe.i_h0lngNk=g3enghf6gNe9eTgNg$g3',
                default => '|9On@3|HkW#-F_:Ql:-U+^00tl:6RPNGpIv~R5Xm7|*|pIT{M{4.W;k=Gt00*|RPQ-L1#8NGrq#8BTaJt,cYI:xaKiIoiw00xaRPR5VEELO?PAWBG[xGo}O?tRFwS~#S:+00xuRPVYL1C6J7FJIo^+pcofOXQ-rXJRPAx]',
            },
            (new BlurHash(9, 9))->encode(__DIR__ . '/images/3.png')
        );
    }

    public function testEncodeDifferentImageWidth(): void
    {
        $hash = new BlurHash();

        $this->assertSame(
            match (PHP_OS_FAMILY) {
                'Darwin', 'Windows' => 'LxTP#UtRe9t7*Jj[f6kCh0enene.',
                default => 'LxTPr6tRe9t7*Jj[f6kCh0enene.',
            },
            $hash->encode(__DIR__ . '/images/4.png')
        );

        $hash->setResizedImageMaxWidth(48);

        $this->assertSame(
            match (PHP_OS_FAMILY) {
                'Darwin', 'Windows' => 'LyTPVWtRe.t7.mkCfkkWh0enene.',
                default => 'LUQr@dx]nOofY*ozkCNuC+rrrXjZ',
            },
            $hash->encode(__DIR__ . '/images/4.png')
        );

        $hash->setResizedImageMaxWidth(32);

        $this->assertSame(
            match (PHP_OS_FAMILY) {
                'Darwin', 'Windows' => 'L*TOwtxaeTxu?^kCf6kWhKe.enf6',
                default => 'L$TOqcxue9xu?^kCe.kWhKe.eTfk',
            },
            $hash->encode(__DIR__ . '/images/4.png')
        );

        $hash->setResizedImageMaxWidth(96);

        $this->assertSame(
            match (PHP_OS_FAMILY) {
                'Darwin', 'Windows' => 'LrTQ13ozeTo}*Jfkf6f+h0enemen',
                default => 'LVQGj_X8rXbvLzWBV@bbCQrrrXni',
            },
            $hash->encode(__DIR__ . '/images/4.png')
        );
    }

    public function testEncodeWithGifFormat(): void
    {
        $hash = new BlurHash();

        $this->assertSame(
            match (PHP_OS_FAMILY) {
                'Darwin', 'Windows' => 'LdIiLaD+%Mt75xIUoHWBR2oIV=WB',
                default => 'LdIYw#D+%Mt75xIUoHWBR2oIV=WB',
            },
            $hash->encode(__DIR__ . '/images/6.gif')
        );
    }
}
