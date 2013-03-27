<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace ProxyManagerTest\ProxyGenerator\AccessInterceptorValueHolder\PhpMethod;

use ReflectionClass;
use PHPUnit_Framework_TestCase;
use ProxyManager\ProxyGenerator\AccessInterceptorValueHolder\PhpMethod\MagicUnset;

/**
 * Tests for {@see \ProxyManager\ProxyGenerator\AccessInterceptorValueHolder\PhpMethod\MagicUnset}
 *
 * @author Marco Pivetta <ocramius@gmail.com>
 * @license MIT
 */
class MagicUnsetTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \ProxyManager\ProxyGenerator\AccessInterceptorValueHolder\PhpMethod\MagicUnset::__construct
     */
    public function testBodyStructure()
    {
        $reflection         = new ReflectionClass('ProxyManagerTestAsset\\EmptyClass');
        $valueHolder        = $this->getMock('CG\\Generator\\PhpProperty');
        $prefixInterceptors = $this->getMock('CG\\Generator\\PhpProperty');
        $suffixInterceptors = $this->getMock('CG\\Generator\\PhpProperty');

        $valueHolder->expects($this->any())->method('getName')->will($this->returnValue('bar'));
        $prefixInterceptors->expects($this->any())->method('getName')->will($this->returnValue('pre'));
        $suffixInterceptors->expects($this->any())->method('getName')->will($this->returnValue('post'));

        $magicUnset = new MagicUnset($reflection, $valueHolder, $prefixInterceptors, $suffixInterceptors);

        $this->assertSame('__unset', $magicUnset->getName());
        $this->assertCount(1, $magicUnset->getParameters());
        $this->assertGreaterThan(
            0,
            strpos($magicUnset->getBody(), 'unset($this->bar->$name);' . "\n\n" . '$returnValue = true;')
        );
    }
}