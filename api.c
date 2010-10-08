#include <stdio.h>
#include <stdlib.h>
#include <math.h>
#include <time.h>
#include "api.h"

int sea_malloc(SEA *sea) {
  int i;
  if ((sea->array = malloc(sea->xs * sizeof(void*)))==NULL)
    return(-1);
  
  for (i=1;i<=sea->xs;i++) {
    if ((sea->array[i] = malloc(sea->ys * sizeof(SEAFIELD)))==NULL)
      return(-1);
  }
  return(0);
/*
  if ((sea->array = ( SEAFIELD ** ) malloc(sea->xs * sizeof(SEAFIELD *)))==NULL)
    return(-1);

  for (i=0;i<sea->xs;i++) {
    if ((sea->array[i] = ( SEAFIELD * ) malloc(sea->ys * sizeof(SEAFIELD)))==NULL)
      return(-1);
  }
  return(0);*/
}

void sea_free(SEA *sea) {
  int i;
  for (i=1;i<=sea->xs;i++) {
    free((void*) sea->array[i]);
  }
  free((void*) sea->array);
}

void sea_fill(SEA sea, SEAFIELD fill) {
  int x, y;
  for (y=1;y<=sea.ys;y++) {
    for (x=1;x<=sea.xs;x++) {
      sea.array[x][y]=fill;
    }
  }
}

void sea_randomize(SEA sea) {
  int x, y, min=sea.mines;
  
  srand(time(NULL));
  while (min>0) {
    x=1+((double)(rand())/(double)RAND_MAX)*(double)sea.xs;
    y=1+((double)(rand())/(double)RAND_MAX)*(double)sea.ys;
    if (sea.array[x][y].mine==0) {
      sea.array[x][y].mine=1;
      min--;
    }
  }
}

void sea_count_mines(SEA sea) {
  int x, y, i, okolo;
  int xsmer[]={ 1, 1, 1, 0,-1,-1,-1, 0};
  int ysmer[]={-1, 0, 1, 1, 1, 0,-1,-1};
  
  for (y=1;y<=sea.ys;y++) {
    for (x=1;x<=sea.xs;x++) {
      okolo=0;
      for (i=0;i<8;i++) {
        if ((x+xsmer[i])>=1 && (x+xsmer[i])<=sea.xs && //lezi souradnice
            (y+ysmer[i])>=1 && (y+ysmer[i])<=sea.ys) { //v hracim poli?
          if ((sea.array[x+(xsmer[i])][y+(ysmer[i])].mine)==1) {
            okolo++;
          }
        }
      }
      sea.array[x][y].count=okolo;
    }
  }
}

int sea_load(SEA *sea, char *file) {
  int x, y;
  char pomc;
  for (y=1;y<=sea->ys;y++) {
    for (x=1;x<=sea->xs;x++) {
      do {
        pomc=fgetc((FILE*)file);
        if (pomc!='0' && pomc!='1' && pomc!=10) {
          printf("neuspesne nacteni souboru!\n");
          return(-1);
        }
        if (pomc!=10) sea->array[x][y].mine=(int)(pomc-48);
      } while (pomc==10);
    }
  }
  return(0);
}

void sea_hide(SEA sea) {
  int x, y;
  for (y=1;y<=sea.ys;y++) {
    for (x=1;x<=sea.xs;x++) {
      sea.array[x][y].hidden=1; //schovame kazde pole
    }
  }
}

int sea_flag(SEA sea, int x, int y) {
  if (x>=1 && x<=sea.xs && y>=1 && y<=sea.ys) {
    if (sea.array[x][y].flag) {
      sea.array[x][y].flag=0; //odznacime
      return(0);
    }
    else {
      if (sea.array[x][y].hidden==1) sea.array[x][y].flag=1; //oznacime
      return(-1);
    }
  }
  else return(-3);
}

int sea_uncover(SEA sea, int x, int y) {
  int i, j, vyhovujici=1;
  int xsmer[8]={ 1, 1, 1, 0,-1,-1,-1, 0};
  int ysmer[8]={-1, 0, 1, 1, 1, 0,-1,-1};
  
  if (x>=1 && x<=sea.xs && y>=1 && y<=sea.ys) { //policko je uvnitr
    if (sea.array[x][y].flag==0) {              //policko neni oznacene
      if (sea.array[x][y].hidden==1) {          //je skryte
        if (sea.array[x][y].mine==0) {          //neni na nem mina
          
          sea.array[x][y].hidden=0;             //!! odkryjeme pole !!
          
          if (sea.array[x][y].count==0) {       //v okoli nejsou miny
            for (i=0;i<8;i++) {
              if ((x+xsmer[i])>=1 && (x+xsmer[i])<=sea.xs && //lezi souradnice
                  (y+ysmer[i])>=1 && (y+ysmer[i])<=sea.ys) { //v hracim poli?
                if (sea.array[x+xsmer[i]][y+ysmer[i]].hidden==1) {
                  sea_uncover(sea,x+xsmer[i],y+ysmer[i]); //odkryjeme neodkryte
                }
              }
            }
          }
          
          for (i=1;i<=sea.xs;i++) {
            for (j=1;j<=sea.ys;j++) { //neni na nem mina a je zakryte
              if (sea.array[i][j].mine==0 && sea.array[i][j].hidden==1) {
                vyhovujici=0;
              }
            }
          }
          if (vyhovujici==1) return(2); //vyhrali jsme :-P
          else return(0);               //odkryto
        }
        else return(1);                 //na policku je mina
      }
      else return(-1);                  //policko uz je objevene
    }
    else return(-2);                    //policko je oznacene
  }
  else return(-3);                      //policko je mimo
}

int sea_uncover_block(SEA sea, int x, int y) {
  int i, ret, retur;
  int xsmer[8]={ 1, 1, 1, 0,-1,-1,-1, 0};
  int ysmer[8]={-1, 0, 1, 1, 1, 0,-1,-1};
  if (x>=1 && x<=sea.xs && y>=1 && y<=sea.ys) {
    ret=sea_uncover(sea,x,y);
    if (retur!=1 && ret>retur) retur=ret;
    
    for (i=0;i<8;i++) {
      if ((x+xsmer[i])>=1 && (x+xsmer[i])<=sea.xs && //lezi souradnice
          (y+ysmer[i])>=1 && (y+ysmer[i])<=sea.ys) { //v hracim poli?
        if (sea.array[x+xsmer[i]][y+ysmer[i]].hidden==1) {
          ret=sea_uncover(sea,x+xsmer[i],y+ysmer[i]);
          if (retur!=1 && ret>retur) retur=ret;
        }
      }
    }
  }
  return(retur);
}

void sea_uncover_mines(SEA sea) {
  int x, y;
  for (y=1;y<=sea.ys;y++) {
    for (x=1;x<=sea.xs;x++) {
      if(sea.array[x][y].mine==1) sea.array[x][y].hidden=0;
    }
  }
}
void sea_flag_mines(SEA sea) {
  int x, y;
  for (y=1;y<=sea.ys;y++) {
    for (x=1;x<=sea.xs;x++) {
      sea.array[x][y].hidden=0;
      if (sea.array[x][y].mine==1)
        sea.array[x][y].flag=1;
    }
  }
}
