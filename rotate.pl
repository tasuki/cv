#!/usr/bin/perl -w
use strict;

# this script takes sgf files as arguments and rotates position in each one
# to the upper left corner, the outpt can be processed with something like:
# 
# for $i in `seq 1 100` do
#   sgf2dg -b `head -n 1 tsumego$i` -twoColumn tsumego$i
# done
# 
# where we have files named tsumego1 through tsumego100 containing the sgf's
# -b tells sgf2dg the height of diagram (which is put on first line by this script)
#
# tasuki 2006, GPL or whatever :)


my @abeceda=("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s");
my @ABECEDA=("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S");
my @rotation; my $rot;
my $fname;
my $i; my $j;
my $maxstr; my @height; my @width; my $minheight=20; my $minwidth=20;
my $line;

sub extreme {
  my $max; my $len; my $charr; my $i; my $j;
  $max=0;
  $len=length($maxstr);                # count the length of the string
  for ($i=0; $i<$len; $i++) {
    $charr=substr($maxstr,$i,1);       # take next char
    for ($j=0; $j<19; $j++) {          # cycle alphabet to find our char
      if ($charr eq $abeceda[$j]) {    # found it?
        if ($j>$max) {                 # how far is it? further than furthest?
          $max=$j;
        }
      }
    }
  }
  return($max);
}

foreach $fname (@ARGV) {
  open(FILE, $fname) or die("Could not open $fname\n");
  $rotation[0]="";
  while($line = <FILE>) {
    $rotation[0].=$line;   # append lines
  }

  # this part is getting dirty; correctly, there should be some kind of sgf parser
  # now we just use custom commands to get rid of the parts of sgf we don't need
  # basically there should be just 

  #$rotation[0]=~ s/PL/\nPL/g;
  $rotation[0]=~ s/\(;GM\[1\]FF\[3\]SZ\[19\]//g;
  $rotation[0]=~ s/PL.*//sg;

  # end of pseudoparser

  #print "$rotation[0]\n";             # print the original, useful for testing
  
  for ($rot=1; $rot<8; $rot++) {       # do the 7 rotations, leave the first one be
    if ($rot<4) {                      # first 4 rotations
      $rotation[$rot]=$rotation[0];    # copy the original to make changes to
      for ($i=0; $i<19;$i++) {
        $j=18-$i;
        if ($rot==1 || $rot==3) {      # switch the first chars for 1, 3
          $rotation[$rot]=~ s/\[$abeceda[$i]/\[$ABECEDA[$j]/g;
        }
        if ($rot==2 || $rot==3) {      # switch the second chars for 2, 3
          $rotation[$rot]=~ s/$abeceda[$i]\]/$ABECEDA[$j]\]/g;
        }
      }
    }
    else {                             # mirror rotations for 4, 5, 6, 7
      $rotation[$rot]=$rotation[$rot-4];
      $rotation[$rot]=~ s/\[(.)(.)\]/\[$2$1\]/g;
    }
    
    for ($i=0; $i<19;$i++) {           # clean of uppercase
      $rotation[$rot]=~ s/\[$ABECEDA[$i]/\[$abeceda[$i]/g;
      $rotation[$rot]=~ s/$ABECEDA[$i]\]/$abeceda[$i]\]/g;
    }
  }
  
  for ($rot=0; $rot<8; $rot++) {       # for all rotations
    
    $maxstr=$rotation[$rot];
    $maxstr=~ s/\[.//g;                # get rid of first letters
    $height[$rot]=extreme;             # get height
    
    $maxstr=$rotation[$rot];
    $maxstr=~ s/.\]//g;                # get rid of second letters
    $width[$rot]=extreme;              # get width
    
    if ($height[$rot]<$minheight) {
      $minheight=$height[$rot];        # find minimal height
    }
  }
  
  for ($rot=0; $rot<8; $rot++) {
    if ($height[$rot]==$minheight) {
      if ($width[$rot]<$minwidth) {
        $minwidth=$width[$rot];        # compute minimal width of the ones with min height
      }
    }
  }
  
  for ($rot=0; $rot<8; $rot++) {       # print the min height + min possible width one
    if ($height[$rot]==$minheight && $width[$rot]==$minwidth) {
      $minheight+=2; # add two to make more space
      $minwidth+=2;
      print "$minheight\n$minwidth\n(;$rotation[$rot])\n"; # add minheight+minwidth to the output
    }
  }
  
  close(FILE);
}
