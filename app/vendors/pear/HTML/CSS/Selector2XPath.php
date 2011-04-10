<?php
/**
 * HTML_CSS_Selector2XPath.php
 *
 * @author Daichi Kamemoto <daikame@gamil.com>
 * @author TANAKA Koichi <tanaka@ensites.com>
 */
/**
 * The MIT License
 *
 * Copyright (c) 2008 Daichi Kamemoto <daikame@gmail.com>
 * Copyright (c) 2009 Daichi Kamemoto <daikame@gmail.com>, TANAKA Koichi <tanaka@ensites.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * HTML_CSS_Selector2XPath CSSセレクタをXPathクエリに置換する。
 *
 * HTML::Selector::XPath
 * http://search.cpan.org/dist/HTML-Selector-XPath/lib/HTML/Selector/XPath.pm
 * のPHP移殖版
 * 
 *
 * @package HTML_CSS_Mobile
 * @version $id$
 * @copyright 2008-2009 authors
 * @author Daichi Kamemoto(a.k.a yudoufu) <daikame@gmail.com>
 * @author TANAKA Koichi(a.k.a MugeSo) <tanaka@ensites.com>
 * @license MIT License
 */
class HTML_CSS_Selector2XPath
{
  /**
   * マッチングに使用する正規表現
   */
  private static $regex = array(
      'element'    => '/^(\*|[a-z_][a-z0-9_-]*|(?=[#:.\[]))/i',
      'id_class'   => '/^([#.])([a-z0-9*_-]*)/i',
      'attribute'  => '/^\[\s*([^~|=\s]+)\s*([~|]?=)\s*"([^"]+)"\s*\]/',
      'attr_box'   => '/^\[([^\]]*)\]/',
      'attr_not'   => '/^:not\(([^)]*)\)/i',
      'pseudo'     => '/^:([a-z0-9_-]+)(\(\s*([a-z0-9_\s+-]+)\s*\))?/i',
    //      'combinator' => '/^(\s*[>+~\s])/i',
    //      'comma'      => '/^(,)/',
      'combinator_or_comma' => '/^(\s*[>+~\s,])/i',
  );

  /**
   * toXPath CSSセレクタをXPathクエリに再構成する
   *
   * @param string $input_selector
   * @param bool $throw_exception trueなら例外を投げます デフォルトfalse
   * @return string
   */
  public static function toXPath($input_selector, $throw_exception = false)
  {
    $parts = array();

    $parts[] = '//';

    $last = '';
    $selector = trim($input_selector);

    $element = true;
    while ((strlen(trim($selector)) > 0) && ($last != $selector))
    {
      $selector = trim($selector);
      $last = trim($selector);

      // Elementを取得
      if($element) {
        if (self::pregMatchDelete(self::$regex['element'], $selector, $e))
        {
          $parts[] = $e[1]==='' ? '*' : $e[1];
        } elseif($throw_exception) {
          throw new UnexpectedValueException("parser error: '$input_selector' is not valid selector.(missing element)");
        }
        $element = false;
      }

      // IDとClassの指定を取得
      if (self::pregMatchDelete(self::$regex['id_class'], $selector, $e))
      {
        switch ($e[1])
        {
          case '.':
            $parts[] = '[contains(concat( " ", @class, " "), " ' . $e[2] . ' ")]';
            break;
          case '#':
            $parts[] = '[@id="' . $e[2] . '"]';
            break;
          default:
            if($throw_exception) throw new LogicException("Unexpected flow occured. please conntact authors.");
            break;
        }
      }

      // atribauteを取得
      if (self::pregMatchDelete(self::$regex['attribute'], $selector, $e))
      {
        // 二項(比較)
        switch ($e[2])
        {
          case '!=':
            $parts[] = '[@' . $e[1] . '!=' . $e[3] . ']';
            break;
          case '~=':
            $parts[] = '[contains(concat( " ", @' . $e[1] . ', " "), " ' . $e[3] . ' ")]';
            break;
          case '|=':
            $parts[] = '[@' . $e[1] . '="' . $e[3] . '" or starts-with(@' . $e[1] . ', concat( "' . $e[3] . '", "-"))]';
            break;
          default:
            $parts[] = '[@' . $e[1] . '="' . $e[3] . '"]';
            break;
        }
      }
      else if (self::pregMatchDelete(self::$regex['attr_box'], $selector, $e))
      {
        // 単項(存在性)
        $parts[] = '[@' . $e[1] . ']';
      }

      // notつきのattribute処理
      if (self::pregMatchDelete(self::$regex['attr_not'], $selector, $e))
      {
        if (self::pregMatchDelete(self::$regex['attribute'], $e[1], $sub_e))
        {
          // 二項(比較)
          switch ($sub_e[2])
          {
            case '=':
              $parts[] = '[@' . $sub_e[1] . '!=' . $sub_e[3] . ']';
              break;
            case '~=':
              $parts[] = '[not(contains(concat( " ", @' . $sub_e[1] . ', " "), " ' . $sub_e[3] . ' "))]';
              break;
            case '|=':
              $parts[] = '[not(@' . $sub_e[1] . '="' . $sub_e[3] . '" or starts-with(@' . $sub_e[1] . ', concat( "' . $sub_e[3] . '", "-")))]';
              break;
            default:
              break;
          }
        }
        else if (self::pregMatchDelete(self::$regex['attr_box'], $e[1], $e))
        {
          // 単項(存在性)
          $parts[] = '[not(@' . $e[1] . ')]';
        }
      }

      // 疑似セレクタを処理
      if (self::pregMatchDelete(self::$regex['pseudo'], $selector, $e))
      {
        switch ($e[1])
        {
          case 'first-child':
            $parts[] = '[not(preceding-sibling::*)]';
            break;
          case 'last-child':
            $parts[] = '[not(following-sibling::*)]';
            break;
          case 'nth-child':
            // CSS3
            if (is_numeric($e[3]))
            {
              $parts[] = '[count(preceding-sibling::*) = ' . $e[3] . ' - 1]';
            } else if ($e[3] == 'odd') {
              $parts[] = '[count(preceding-sibling::*) mod 2 = 0]';
            } else if ($e[3] == 'even') {
              $parts[] = '[count(preceding-sibling::*) mod 2 = 1]';
            } else if (preg_match('/^([+-]?)(\d*)n(\s*([+-])\s*(\d+))?\s*$/i', $e[3], $sub_e)) {
              $coefficient = $sub_e[2]==='' ? 1 : intval($sub_e[2]);
              $constant_term = array_key_exists(3, $sub_e) ?  intval($sub_e[4]==='+' ? $sub_e[5] : -1 * $sub_e[5]) : 0;
              if($sub_e[1]==='-') {
                $parts[] = '[(count(preceding-sibling::*) + 1) * ' . $coefficient . ' <= ' . $constant_term . ']';
              } else { // '+' or ''
                $parts[] = '[(count(preceding-sibling::*) + 1) ' . ($coefficient===0 ? '': 'mod ' . $coefficient . ' ') . '= ' . ($constant_term>=0 ? $constant_term : $coefficient + $constant_term) . ']';
              }
            }
            break;
          case 'lang':
            $parts[] = '[@xml:lang="' . $e[3] . '" or starts-with(@xml:lang, "' . $e[3] . '-")]';
            break;
          default:
            break;
        }
      }

      // combinatorとカンマがあったら、区切りを追加。
      // また、次は型選択子又は汎用選択子でなければならない
      if (self::pregMatchDelete(self::$regex['combinator_or_comma'], $selector, $e))
      {
        switch (trim($e[1]))
        {
          case ',':
            $parts[] = ' | //';
            break;
          case '>':
            $parts[] = '/';
            break;
          case '+':
            $parts[] = '/following-sibling::*[1]/self::';
            break;
          case '~':
            // CSS3
            $parts[] = '/following-sibling::';
            break;
          //          case '':
          default:
            $parts[] = '//';
            break;
        }
        $element = true;
      }
    }

    $return = implode('', $parts);

    return $return;
  }

  /**
   * pregMatchDelete 正規表現でマッチをしつつ、マッチ部分を削除
   *
   * @param string $pattern
   * @param string $subject
   * @param array $matches
   * @return boolean
   */
  public static function pregMatchDelete($pattern, &$subject, &$matches)
  {
    $result = false;
    if (preg_match($pattern, $subject, $matches))
    {
      $subject = substr($subject, strlen($matches[0]));
      $result = true;
    }

    return $result;
  }

}
