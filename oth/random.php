<?php
/**
 * Generate random pronounceable words
 *
 * @param int $length Word length
 * @return string Random word
 */
function random_pronounceable_word($length = 6 ) {
    
    // consonant sounds
    $cons = array(
        // single consonants. Beware of Q, it's often awkward in words
        'b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm',
        'n', 'p', 'r', 's', 't', 'v', 'w', 'x', 'z',
        // possible combinations excluding those which cannot start a word
        'pt', 'gl', 'gr', 'ch', 'ph', 'ps', 'sh', 'st', 'th', 'wh', 
    );
    
    // consonant combinations that cannot start a word
    $cons_cant_start = array( 
        'ck', 'cm',
        'dr', 'ds',
        'ft',
        'gh', 'gn',
        'kr', 'ks',
        'ls', 'lt', 'lr',
        'mp', 'mt', 'ms',
        'ng', 'ns',
        'rd', 'rg', 'rs', 'rt',
        'ss',
        'ts', 'tch', 
    );
    
    // wovels
    $vows = array(
        // single vowels
        'a', 'e', 'i', 'o', 'u', 'y', 
        // vowel combinations your language allows
        'ee', 'oa', 'oo', 
    );
    
    // start by vowel or consonant ?
    $current = ( mt_rand( 0, 1 ) == '0' ? 'cons' : 'vows' );
    
    $word = '';
        
    while( strlen( $word ) < $length ) {
    
        // After first letter, use all consonant combos
        if( strlen( $word ) == 2 ) 
            $cons = array_merge( $cons, $cons_cant_start );
 
         // random sign from either $cons or $vows
        $rnd = ${$current}[ mt_rand( 0, count( ${$current} ) -1 ) ];
        
        // check if random sign fits in word length
        if( strlen( $word . $rnd ) <= $length ) {
            $word .= $rnd;
            // alternate sounds
            $current = ( $current == 'cons' ? 'vows' : 'cons' );
        }
    }
    
    return $word;
}

// echo random_pronounceable_word(6);

$c  = 'bcdfghjklmnprstvwz'; //consonants except hard to speak ones
$v  = 'aeiou';              //vowels
$a  = $c.$v;                //both
 
for ($i = 1; $i <= 100; $i++) {
    $pw = '';
    
    //use two syllables...
    for($j=0;$j < 2; $j++){
        $pw .= $c[rand(0, strlen($c)-1)];
        $pw .= $v[rand(0, strlen($v)-1)];
        $pw .= $a[rand(0, strlen($a)-1)];
    }
 
    echo $pw . "<br/>";
}
	
?>