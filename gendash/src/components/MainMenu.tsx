/* Library Imports */
//React
import React from "react";

/* Stylesheet Imports */

/* Image Imports */

/* Component Imports */

/* Module Imports */

/* Component Interfaces */
interface Props {
  modeSetter: (mode: string) => void;
}

/* Component */
const MainMenu: React.FC<Props> = ({ modeSetter }) => {
  /* State Variables */

  /* End State Variables */

  /* Render Variables */
  /* End Render Variables */

  /* Functions */
  /* End Functions */

  /* Effects */
  /* End Effects */

  /* Component Return Statement */
  return (
    <div className="MainMenu grid grid-cols-2 w-3/5 mx-auto gap-3">
      <button
        className="bg-orange-600 px-6 py-2 hover:bg-orange-800"
        onClick={() => modeSetter("watchdog")}
      >
        Watchdog
      </button>
      <button
        className="bg-green-600 px-6 py-2 hover:bg-green-800"
        onClick={() => modeSetter("data")}
      >
        Data
      </button>
      <button
        className="bg-yellow-600 px-6 py-2 hover:bg-yellow-800"
        onClick={() => modeSetter("asins")}
      >
        ASINS
      </button>
    </div>
  );
};

/* Export Statement */
export default MainMenu;
