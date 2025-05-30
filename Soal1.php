<?php
for ($i = 0; $i < 7; $i++) {
  for ($j = 0; $j < 7; $j++) {
    if ($i === $j || $i + $j === 6) {
      echo "X ";
    } else {
      echo "O ";
    }
  }
  echo "\n"; 
}
?>
