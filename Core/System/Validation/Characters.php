<?php

namespace System\Validation;

class Characters
{
  private $excepts;
  private $chars;
  private $times;
  private $atFirst;
  private $atEnd;
  private $between;
  private $langsRegex;
  private $languages;

  /**
   * Constructor
   *
   */
  public function __construct($excepts, $value)
  {
    $this->excepts = $excepts;
    $this->chars = $this->excepts->chars->value ?? $this->excepts->chars->chars ?? null;
    $this->times = $this->excepts->chars->times ?? null;
    $this->atFirst = $this->excepts->chars->atFirst ?? null;
    $this->atEnd = $this->excepts->chars->atEnd ?? null;
    $this->between = $this->excepts->chars->between ?? null;
    $this->languages = $this->excepts->languages ?? 'english';
    $this->langsRegex = $this->excepts->languages ?? $this->languagesArray('english');
    $this->value = $value ?? null;

    $this->setChars();
    $this->setLanguages();
  }

  private function isExcepts()
  {
    return is_object($this->excepts) && count((array) $this->excepts);
  }

  private function isCharsString()
  {
    return is_string($this->chars);
  }

  private function isCharsAnArray()
  {
    return is_array($this->chars);
  }

  private function canCharsSeparateViaComma()
  {
    return preg_match('/,/', $this->chars) && preg_match_all('/,/', $this->chars) > 1;
  }

  private function formatCharsViaComma($comma)
  {
    if ($comma) {
      $chars = explode(',', $this->chars);
    } else {
      $chars = str_split($this->chars);
    }
    return "\\" . implode('\\', $chars);
  }

  private function formatCharsString()
  {
    if ($this->canCharsSeparateViaComma()) {
      return $this->formatCharsViaComma(true);
    } else {
      return $this->formatCharsViaComma(false);
    }
  }

  private function formatCharsArray()
  {
    return implode('', (array) $this->chars);
  }

  private function setChars()
  {
    if ($this->isExcepts()) {
      if ($this->isCharsString()) {
        $this->chars = $this->formatCharsString();
      } else if ($this->isCharsAnArray()) {
        $this->chars = $this->formatCharsArray();
      }
    }
  }

  private function languagesArray($language)
  {
    $languages = [
      'all' => '\\p{L}',
      'arabic' =>  '\\x{0621}-\\x{064A}\\x{0660}-\\x{0669} ُ ْ َ ِ ّ~ ً ٍ ٌ',
      'english' => 'a-z',
      'spanish' => 'a-zñ',
      'french' => 'a-zàâçéèêëîïôûùüÿñæœ',
      'german' => 'a-zäüöß',
    ];
    return $languages[$language] ?? $languages['english'];
  }

  private function isLangsAnArray()
  {
    return is_array($this->languages);
  }

  private function isLangsAnString()
  {
    return is_string($this->languages);
  }

  private function canlangsSeparateViaComma()
  {
    return preg_match('/,/', $this->languages) && preg_match_all('/,/', $this->languages);
  }

  private function loopOverLangsViaComma($comma)
  {
    $loopLangs = $comma ? explode(',', $this->languages) : $this->languages;
    $langsRegex = '';
    $languages = '';
    foreach ($loopLangs as $language) {
      $langsRegex .= $this->languagesArray(trim($language));
      $languages .= "$language, ";
    }
    $languages = rtrim($languages, ", ");
    return array('languages' => $languages, 'langsRegex' => $langsRegex);
  }

  private function formatLangsString()
  {
    if ($this->canlangsSeparateViaComma()) {
      extract($this->loopOverLangsViaComma(true));
    } else {
      $langsRegex = $this->languagesArray(trim($this->languages));
      $languages = $this->languages;
    }
    return array('languages' => $languages, 'langsRegex' => $langsRegex);
  }

  private function setLanguages()
  {
    if ($this->isLangsAnArray()) {
      extract($this->loopOverLangsViaComma(false));
    } else if ($this->isLangsAnString()) {
      extract($this->formatLangsString());
    }
    $this->languages = $languages;
    $this->langsRegex = $langsRegex;
    $this->formatLangsRegex();
  }

  private function formatLangsRegex()
  {
    if ($this->langsRegex !== 'all' && preg_match_all('/a-z/i', $this->langsRegex) > 1) {
      $this->langsRegex = preg_replace('/a-z/', '', $this->langsRegex) . 'a-z';
    }
  }

  public function variables()
  {
    $chars = $this->chars;
    $langsRegex = $this->langsRegex;
    $languages = $this->languages;
    $times = $this->times;
    $atFirst = $this->atFirst;
    $atEnd = $this->atEnd;
    $between = $this->between;
    $methods = $this->charactersMethods([
      "times" => $times,
      "atFirst" => $atFirst,
      "atEnd" => $atEnd,
      "between" => $between,
      "chars" => $chars,
      "value" => $this->value,
    ]);
    return [
      'chars' => $chars,
      'langsRegex' => $langsRegex,
      'languages' => $languages,
      'times' => $times,
      'atFirst' => $atFirst,
      'atEnd' => $atEnd,
      'between' => $between,
      'methods' => $methods,
    ];
  }

  private function charactersMethods($args)
  {
    extract($args);
    return [
      'charactersTimes' => [
        [$times, $chars, $value],
        'charachters are too many',
      ],
      'charactersAtFirst' => [
        [$atFirst, $chars, $value],
        'charachters cant be at the first',
      ],
      'charactersAtEnd' => [
        [$atEnd, $chars, $value],
        'charachters cant be at the end',
      ],
      'charactersBetween' => [
        [$between, $chars, $value],
        'charachters cant be between',
      ],
    ];
  }

  private function charactersFormatCharsRegex($chars)
  {
    if (strlen($chars) > 1) {
      $chars = str_split($chars);
      $chars = "\\" . implode('|\\', $chars);
    }
    return $chars;
  }

  public function charactersAtFirst($atFirst, $chars, $value)
  {
    if ($atFirst === false) {
      $chars = $this->charactersFormatCharsRegex($chars);
      $re = "/^($chars" . "|\\s+\\$chars)/";
      if (preg_match_all($re, $value)) {
        return true;
      }
      return false;
    }
  }

  private function charactersFormatCharsMsg($chars)
  {
    $chars = explode('\\', $chars);
    $chars = implode('', $chars);
    $chars = $chars ? "[ $chars ] and" : '';
    return $chars;
  }

  public function charactersAtEnd($atEnd, $chars, $value)
  {
    if ($atEnd === false) {
      $chars = $this->charactersFormatCharsRegex($chars);
      $re = "/($chars" . "|\\$chars\\s+)$/";
      if (preg_match_all($re, $value)) {
        return true;
      }
      return false;
    }
  }

  public function charactersBetween($between, $chars, $value)
  {
    if ($between === false) {
      $chars = $this->charactersFormatCharsRegex($chars);
      $re = "/.+(${chars})(.+|\\s)/";
      if (preg_match_all($re, $value)) {
        return true;
      }
      return false;
    }
  }

  public function charactersTimes($times, $chars, $value)
  {
    if ($times > 0) {
      $chars = $this->charactersFormatCharsRegex($chars);
      $re = "/($chars)/";
      if (preg_match($re, $value) && preg_match_all($re, $value) > $times) {
        return true;
      }
      return false;
    }
  }

  public function charactersMsg($chars, $languages, $msg)
  {
    $chars = $this->charactersFormatCharsMsg($chars);
    $languages = $languages ? "[ $languages ]" : '';
    return $msg ?: "just $chars $languages letters can be used";
  }
}

