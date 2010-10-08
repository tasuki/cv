/*********************************
 * Goban 3.5          POVRay 3.6 *
 *     Vit 'tasuki' Brunner      *
 *   http://tasuki.tasuki.org/   *
 *********************************/

/* for good result use something like: povray +Igoban.pov +FN +W1024 +H768 +Q9 +QR +A0.1 +AM2 */

#include "colors.inc"


/******************************
 *  go stone equation:        *
 *                            *
 *  x^2 + 4y^2 + z^2 - 1 = 0  *
 ******************************/

#declare Seeed=seed(123);

#macro wstone(xx,yy)         // white stone
  quadric{
    <1,4,1>, <0,0,0>, <0,0,0>, -1
    pigment {
      gradient x
      color_map {
        [0.0 color rgb<0.95,0.95,0.95>]
        [0.2 color rgb<0.91,0.91,0.91>]
        [0.5 color rgb<0.87,0.87,0.87>]
        [0.7 color rgb<0.83,0.83,0.83>]
      }
      scale <.2, .2, .2>
      rotate <0,rand(Seeed)*360,rand(Seeed)*15+20>
    }
    finish {
      //ambient 0.75   // lighter white stones
      ambient 0.6      // darker white stones
      diffuse 0.4
      brilliance 4
      reflection 0.1
    }

    translate x*(2*xx-20)
    translate z*(2*yy-20)
  }
#end

#macro bstone(xx,yy)         // black stone
  quadric {
    <1,4,1>, <0,0,0>, <0,0,0>, -1
    pigment { Black }
    finish {
      phong 0.2
      phong_size 1
    }
    translate x*(2*xx-20)
    translate z*(2*yy-20)
  }
#end


/******************************
 *    kaya wood               *
 ******************************/

#declare kaya = 
texture {
  pigment {
    wood warp {
      turbulence 0.3
    }
    scale <.25, .25, 5>
    rotate <1,-1,0>

    color_map {
      [0.0 color rgb<0.80,0.80,0.42>]
      [0.2 color rgb<0.78,0.78,0.40>]
      [0.5 color rgb<0.75,0.75,0.37>]
      [0.7 color rgb<0.73,0.73,0.35>]
    }
  }
  normal {
    bumps 0.1 scale 0.05
  }
  finish {
    //ambient rgb <0.3,0.1,0.1>   // red
    //ambient rgb <0.4,0.3,0.1>   // yellow
    ambient rgb <0.35,0.2,0.1>   // the right one
    reflection 0.1               // varnish
  }
}


/******************************
 *         goban              *
 ******************************/

#macro grid(RScale, RLine)   // goban lines texture
  pigment {
    gradient x scale RScale
    color_map {
      [0.000   color rgbt<0,0,0,0>]
      [0+RLine color rgbt<0,0,0,0>]
      [0+RLine color rgbt<1,1,1,1>]
      [1-RLine color rgbt<1,1,1,1>]
      [1-RLine color rgbt<0,0,0,0>]
      [1.000   color rgbt<0,0,0,0>]
    }
  }
  finish { ambient 1 diffuse 5 }
#end

box {                        // goban box
  <-19.5, -15,  -19.5>,
  < 19.5,  -0.5, 19.5>
  texture { kaya }
}

box {                        // box with lines
  <-18.04, -1,     -18.04>,
  < 18.04, -0.4999, 18.04>
  texture {
    grid(2, 0.015)
  }
  texture {
    grid(2, 0.015)
    rotate <0,90,0> 
  }    
}

#macro starpoint(xx,zz)      // hoshi
  cylinder {
    <0, -1, 0>, <0, -0.4998, 0>, 0.12  // drive 0.15
    translate x*xx
    translate z*zz

    pigment { Black }
    finish { ambient 1 diffuse 5 }
  }
#end

starpoint (-12,-12)
starpoint (-12,  0)
starpoint (-12, 12)
starpoint (  0,-12)
starpoint (  0,  0)
starpoint (  0, 12)
starpoint ( 12,-12)
starpoint ( 12,  0)
starpoint ( 12, 12)


/******************************
 *    goban legs              *
 ******************************/

#declare legfnc =            // a sphere with eight spines
isosurface {
  function { sqrt(x*x+y*y+z*z) - .9 + abs(sin(4*atan2(x,z))*0.1) }
}

#declare halfleg =           // cut the upper half
difference {
  isosurface { legfnc }
  box { <-5,0,-5>, <5,5,5> }
}

#macro leg(xx,zz)
  union {
    object {                 // lower side of the leg
      halfleg
    }
    isosurface {             // upper side of the lower side ;-)
      legfnc
      scale <1,.2,1>
    }
    object {                 // upper side, larger and longer
      halfleg
      scale <1.2,1.5,1.2>
      translate <0,1.4,0>
    }
    scale 3.6
    rotate y*22.5
    translate y*(-20)
    translate x*xx
    translate z*zz
    
    texture { kaya }
  }
#end

leg (-15,-15)
leg (-15, 15)
leg ( 15,-15)
leg ( 15, 15)


/******************************
 *       Stuff ...            *
 ******************************/

plane { y, -23               // the ground
  pigment { rgb .8 }
  finish {
    ambient .3
    reflection .1  
  }
}

/*plane { y, -23               // the ground
  pigment { rgb .5 }
  finish {
    //ambient .3
    //reflection .1  
  }
}*/

sphere {                     // sky or something
  <0,0,0>, 1000
  hollow
  pigment { rgb <.7,.7,1> }
  finish {
    ambient .1
  }
}

//background { White }


#declare view_normal  = camera { location <-25,  35, -40> look_at <10, -30, 0> }
#declare view_legs    = camera { location <-25, -22, -40> look_at < 0, -20, 0> }
#declare view_detail  = camera { location <-17,  15, -15> look_at <15, -20, 0> }
#declare view_dist    = camera { location <-25,  10, -65> look_at <10, -15, 0> }
#declare view_distant = camera { location <-50,  20,-130> look_at <10, -15, 0> }

camera {
  view_normal
  //view_legs
  //view_detail
  //view_dist
  //view_distant
}

#declare qualight = 5;       // 5 gives a nice enough result, feel free to use higher number though

light_source {
  <-35, 50, 5>
  White
  area_light <50, 0, 0>, <0, 0, 50>, qualight, qualight
  adaptive 1
  jitter
}

light_source {
  <50, 50, -100>
  White
  area_light <100, 0, 0>, <0, 0, 100>, qualight, qualight
  adaptive 1
  jitter
}

// include the stones here:

#include "redear.inc"
//#include "yasuisanchi.inc"
//#include "prob.inc"
