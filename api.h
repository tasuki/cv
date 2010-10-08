#ifndef _API_H
#define _API_H

/* Policko */
typedef struct seafield {
    /* pocet min v okoli */
    unsigned int count:4;
    /* 0 je voda, 1 je mina */
    unsigned int mine:1;
    /* 0 je objeveny, 1 je skryty */
    unsigned int hidden:1;
    /* 0 neoznaceny, 1 oznaceny */
    unsigned int flag:1;
} SEAFIELD;

/* More */
typedef struct sea {
    /* Sem nutno alokovat pole */
    SEAFIELD **array;
    /* velikosti */
    int xs;
    int ys;
    /* pocet min */
    int mines;
} SEA;


/*
 *  Alokuje pamet more (sea.array).
 *
 *  Param:
 *      SEA sea: more (musi mit nastavene sea.xs, sea.ys)
 *  
 *  Navratova hodnota:
 *      0 uspech
 *     -1 neuspech
 */
int sea_malloc(SEA *sea);

/*
 *  Uvolni pamet alokovanou pro more.
 *
 *  Param:
 *      SEA sea: more (musi mit nastavene sea.xs, sea.ys)
 */
void sea_free(SEA *sea);

/*
 *  Naplni vsechna policka more hodnotou fill
 *
 *  Param:
 *      SEA sea: more
 *      SEAFIELD fill: hodnota na naplneni more
 */
void sea_fill(SEA sea, SEAFIELD fill);

/*
 *  Rozhazi do more miny podle nastavene sea.mines.
 */
void sea_randomize(SEA sea);

/*
 *  Na kazdem policku ulozi do count, kolik je kolem min.
 */
void sea_count_mines(SEA sea);

/*
 *  Nacte miny do more ze souboru. Soubor je tvoren znaky '0' a '1', kde 1 je
 *  mina, 0 je voda. Predpoklada platne hodnoty sea.xs, sea.ys. Nastavi
 *  hodnotu sea.mines, ale jen v pripade, ze nacitani probehne uspesne!!!
 *
 *  Param:
 *      SEA sea: more
 *      char *file: cesta souboru, ze ktereho se nacita
 *
 *  Navratova hodnota:
 *      0 uspech
 *     -1 neuspech
 */
int sea_load(SEA *sea, char *file);

/*
 *  Nastavi vsechna policka v mori jako schovana.
 */
void sea_hide(SEA sea);

/*
 *  Oznaci policko na souradnicich. U oznaceneho policka oznaceni zrusi.
 *
 *  Param:
 *      SEA sea: more
 *      x, y: souradnice oznaceni
 *
 *  Navratova hodnota:
 *     -3 souradnice jsou mimo hranice more
 *     -1 policko je uz odkryte
 *      0 v poradku
 */
int sea_flag(SEA sea, int x, int y);

/*
 *  Odkryje policko na souradnicich.
 *
 *  Param:
 *      SEA sea: more
 *      x, y: souradnice odkryti
 *
 *  Navratova hodnota:
 *     -3 souradnice jsou mimo hranice more
 *     -2 policko je oznacene
 *     -1 policko je uz odkryte
 *      0 v poradku
 *      1 mina
 *      2 vitezstvi
 */
int sea_uncover(SEA sea, int x, int y);

/*
 *  Odkryje blok policek 3x3 podle souradnic stredu.
 *
 *  Param:
 *      SEA sea: more
 *      x, y: souradnice odkryti
 *
 *  Navratova hodnota:
 *     -3 souradnice jsou mimo hranice more
 *      0 v poradku
 *      1 mina
 *      2 vitezstvi
 */
int sea_uncover_block(SEA sea, int x, int y);

/*
 *  Odkryje pouze miny. (vola se v pripade prohry)
 */
void sea_uncover_mines(SEA sea);

/*
 *  Oznackuje vsechny miny (vola se v pripade vitezstvi)
 */
void sea_flag_mines(SEA sea);

#endif
