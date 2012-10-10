#include "master.h"

/*****************************************************************************

    Function: main

    Description: Home.
    Parameters: pointer to the UNIVERSE.
    Return: Success

*****************************************************************************/
int main(int argc, char *argv[])
{
    initialize();
    loadData();
    
    while(running)
    {  
        doLogic();
        doDrawing();
        
        blitScreen();
    }

    shutdown();
    
    return 0;
}
