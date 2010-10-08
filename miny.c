#include <stdio.h>
#include <stdlib.h>
#include <math.h>
#include "api.h"
#include "api.c"

#define XMAX 37
#define YMAX 15

void vypis(SEA sea) {
  int i, j;
  char znak;
  printf("    "); //vypis souradnic a podobnych nesmyslu
  for (j=1;j<=sea.xs;j++) {
    if (j/10!=0) printf(" %d",j/10); //prvni radek
    else printf("  ");
  }
  printf("\n    ");
  for (j=1;j<=sea.xs;j++) printf(" %d",j%10); //druhy radek
  printf("\n   +");
  for (j=1;j<=sea.xs;j++) printf("--"); // treti radek
  printf("\n"); //vodorovna cast

  for (i=1;i<=sea.ys;i++) { //zacatek vypisovaciho cyklu
    if (i>=10) printf("%d |",i); //dvouciferne cislo radku
    else printf(" %d |",i); //jednociferne cislo radku
    for (j=1;j<=sea.xs;j++) {
      if (sea.array[j][i].hidden==1) znak='.'; //priradime tecku skrytym polim
      else {
        znak=sea.array[j][i].count+48; //prevedeme cislo na 'cislo'
        if (znak=='0') znak='*'; //ostruvky vypiseme hvezdickama
        if (sea.array[j][i].mine==1) znak='X'; //vypiseme miny
      }
      if (sea.array[j][i].flag==1) znak='F';
      
      printf(" %c",znak); //vypiseme znak
    }
    printf("\n"); //za kazdym radkem udelame novy radek
  }
}

int main(int argc, char *argv[]) {
  FILE *file;
  SEA sea;
  SEAFIELD seafield;
  int x, y, pocet, konec=0, otevreny=0;
  char param[10]="nic", vstup[10];
  
  if (argc==4 || argc==5) {
    sea.xs=atoi(argv[1]); //sirka more
    sea.ys=atoi(argv[2]); //vyska more
    sea.mines=atoi(argv[3]); //pocet min
    if (argc==5) {
      if ((file=fopen(argv[4], "r"))!=NULL) {
        otevreny=1;
      }
    }
  }
  else {
    printf("musi byt tri (nebo ctyri) argumenty - sirka, vyska, pocet min (\n");
    return(1);
  }
  
  if (sea.xs>XMAX || sea.xs<10 || sea.ys>YMAX || sea.ys<10
                         || sea.mines<1 || sea.mines>(sea.xs*sea.ys-10)) {
    printf("oups, sloupcu musi byt 10-%d, radku 10-%d, a min 1 az (x*y-10)\n",
                                                    XMAX,YMAX);
    return(1);
  }
  
  printf("                            .: hledani min :.\n"
         "k odkryti pole zadavejte vstup ve formatu: "
         "cislo_sloupce cislo_radku,\n"
         "pridanim pismene f oznacite/odznacite minu,\n"
         "pridanim pismene x odkryjete blok 3x3 policek, "
         "napsanim \"exit\" ukoncite program\n");
  
  if (sea_malloc(&sea)!=0) {
    printf("nemallocovali jsme\n");
    return(1);
  }
  
  seafield.count=0;
  seafield.mine=0;
  seafield.hidden=0;
  seafield.flag=0;
  
  sea_fill(sea,seafield);
  
  sea_hide(sea); //sea.array[*][*].hidden=1 (skryty)
  
  if (otevreny==1) {
    if ((sea_load(&sea, (char*) file))==-1) otevreny=0;
  }
  if (otevreny==0) {
    sea_randomize(sea); //nahodne vlozime prislusny pocet min
  }
  
  sea_count_mines(sea); //spocitame pocet min v okoli
  
  while (1) {
    
    vypis(sea);
    
    printf("miny$ "); //pseudo shell :-D
    
    fgets(vstup,10,stdin); //nacteme radek
    if (vstup[0]=='e' && vstup[1]=='x' && vstup[2]=='i' && vstup[3]=='t') {
      printf("\n"); //zkontrolujeme na slovo exit
      sea_free(&sea);
      return(0);
    }
    pocet = sscanf(vstup,"%d %d %s",&x,&y,param); //nacteme parametry
    
    if (pocet==2) konec=sea_uncover(sea,x,y); //v pripade dvou parametru
    else {
      if (param[0]=='x') konec=sea_uncover_block(sea,x,y); //blok 3x3
      if (param[0]=='f') sea_flag(sea,x,y); //oznaceni/odznaceni miny
    }
    
    if (konec==1) { //objevena mina
      sea_uncover_mines(sea); //odkryjeme miny
      vypis(sea);
      printf("      juuuuu, nasli jste minu...\n");
      sea_free(&sea);
      return(0);
    }
    if (konec==2) { //vyhra
      sea_flag_mines(sea); //ovlajeckujeme miny
      vypis(sea);
      printf("      vyhrali jste!\n");
      sea_free(&sea);
      return(0);
    }
  }
}
