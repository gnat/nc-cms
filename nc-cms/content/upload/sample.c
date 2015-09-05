#include "master.h"

/**
* Main.
* @param Pointer to the UNIVERSE.
* @return Success.
*/
int main(int argc, char *argv[])
{
    Initialize();
    LoadData();
    NewGame();
    
    while(running)
    {  
        Logic();
        Drawing();
    }

    Shutdown();
    
    return 0;
}
