/* Library Imports */
//React
import React, { useEffect, useState } from "react";

/* Stylesheet Imports */

/* Image Imports */

/* Component Imports */
import { FaSearch } from "react-icons/fa";
import { IoNuclear } from "react-icons/io5";
import { GiMushroomCloud } from "react-icons/gi";

/* Module Imports */
import papa from "papaparse";
import { nukeAsins } from "@/modules/api/asins";

/* Component Interfaces */
interface Props {
  asins: any[];
  actionData: any[];
  dataIsLoading: boolean;
  dataIsFetched: boolean;
}

/* Component */
const ASINBase: React.FC<Props> = ({
  asins,
  actionData,
  dataIsLoading,
  dataIsFetched,
}) => {
  /* State Variables */
  const [mode, setMode] = useState<string>("main");
  const [file, setFile] = useState<any>(null);
  const [data, setData] = useState<any>(null);
  const [procData, setProcData] = useState<any>(null);
  const [deploymentPayload, setDeploymentPayload] = useState<any>(null);
  const [associatedCount, setAssociatedCount] = useState<number>(0);
  const [unassociatedCount, setUnassociatedCount] = useState<number>(0);
  const [first50Associated, setFirst50Associated] = useState<any>(null);
  const [deleteConfirmation, setDeleteConfirmation] = useState<string | null>(
    null
  );

  /* End State Variables */

  /* Render Variables */
  let renderedAsins: any = <p>No asins to display!</p>;

  let renderedPayload: any = <p>No payload to display!</p>;
  /* End Render Variables */

  /* Render Logic */
  if (asins && asins.length > 0) {
    renderedAsins = asins.map((asin) => {
      return (
        <div className="flex flex-row justify-evenly my-2 py-2 border border-black">
          <h3 className="w-[40%] text-center">ASIN: {asin.asin}</h3>
          <h3 className="w-[40%] text-center">SKU: {asin.sku}</h3>
        </div>
      );
    });
  }

  if (first50Associated && first50Associated.length > 0) {
    renderedPayload = first50Associated.map((item: any) => {
      return (
        <div className="depItem flex flex-row my-3 text-center items-center py-2 border border-black">
          <h3 className="w-[40%] text-center">TITLE: {item.title}</h3>
          <h3 className="w-[40%] text-center">SKU: {item.sku}</h3>
          <h3 className="w-[40%] text-center">ASIN: {item.asin}</h3>
        </div>
      );
    });
  }
  /* End Render Logic */

  /* Functions */
  function buildAssociations() {
    if (!procData) {
      return alert("Please process data from the csv file to continue.");
    }

    if (actionData && actionData.length > 0) {
      const payload = actionData.map((item: any) => {
        const target = procData.find((pd: any) => pd.sku === item.sku);
        if (target) {
          return { ...item, asin: target.asin };
        }
        if (!target) {
          return { ...item, asin: undefined };
        }
      });

      setDeploymentPayload(payload);
    }
  }

  function parseFile() {
    if (!file) {
      return alert("No file to parse!");
    }

    papa.parse(file, {
      header: true,
      complete: (result) => {
        setData(result.data);
      },
    });
  }
  /* End Functions */

  /* Effects */
  useEffect(() => {
    if (data && data.length > 0) {
      const payload = data.map((item: any) => {
        return { asin: item["(Child) ASIN"], sku: item.SKU };
      });
      setProcData(payload);
    }
  }, [data]);

  useEffect(() => {
    if (deploymentPayload && deploymentPayload.length > 0) {
      const associatedCount = deploymentPayload.filter(
        (item: any) => item.asin
      ).length;
      setAssociatedCount(associatedCount);
      const unassociatedCount = deploymentPayload.filter(
        (item: any) => !item.asin
      ).length;
      setUnassociatedCount(unassociatedCount);
      const first50Associated = deploymentPayload
        .filter((item: any) => item.asin)
        .slice(0, 50);
      setFirst50Associated(first50Associated);
    }
  }, [deploymentPayload]);

  /* End Effects */

  /* Component Return Statement */
  if (mode === "delete") {
    return (
      <div className="ASINBase w-[70%] mx-auto">
        <div className="delBox flex flex-col justify-center">
          <h2 className="text-3xl text-center">
            You really want to delete all the ASINS, you naughty boy?
          </h2>
          <p className="text-2xl text-center">
            K, I guess. Type 'delete' below.
          </p>
          <input
            type="text"
            className="border border-black pl-3 w-[50%] mx-auto my-3"
            onChange={(e) => setDeleteConfirmation(e.target.value)}
          />

          {deleteConfirmation && deleteConfirmation === "delete" ? (
            <button
              className="bg-red-500 hover:bg-red-700 py-3 w-[40%] block mx-auto"
              onClick={() => {
                nukeAsins();
                setMode("main");
                setDeleteConfirmation(null);
              }}
            >
              DELETE
            </button>
          ) : null}
        </div>
      </div>
    );
  }

  if (mode === "bulk") {
    return (
      <div className="ASINBase w-[70%] mx-auto">
        <div className="flex flex-row justify-center">
          <div className="w-[48%] flex flex-col justify-center">
            <h2 className="text-2xl text-center">Part One:</h2>
            <p className="mb-4 text-center">Choose Amazon Report File</p>
            {file ? (
              <h2 className="text-3xl text-center text-green-500">
                File Selected
              </h2>
            ) : (
              <input
                type="file"
                name=""
                id=""
                className="w-[50%] mx-auto block"
                onChange={(e: any) => setFile(e.target.files[0])}
              />
            )}
          </div>
          <div className="w-[48%] flex flex-col justify-center">
            <h2 className="text-2xl text-center">Part Two:</h2>
            <p className="mb-4 text-center">Await Query Data to Load.</p>
            {dataIsLoading ? (
              <p className="text-3xl text-center text-yellow-500">Loading...</p>
            ) : null}
            {dataIsFetched ? (
              <p className="text-3xl text-center text-green-500">Data Loaded</p>
            ) : null}
          </div>
        </div>
        {file && !procData ? (
          <div className="mainActions flex flex-row  my-5 justify-center w-[50%] mx-auto gap-4">
            <button
              className="w-[30%] bg-green-400 py-3 hover:bg-green-600"
              onClick={() => parseFile()}
            >
              PARSE FILE
            </button>
            <button className="w-[30%] bg-red-400 py-3">CLEAR FILE</button>
          </div>
        ) : (
          <div className="stepTwoActions flex flex-row  my-5 justify-center w-[50%] mx-auto gap-4">
            <button disabled className="w-[30%] bg-gray-400 py-3">
              PARSE FILE
            </button>
            <button disabled className="w-[30%] bg-gray-400 py-3">
              CLEAR FILE
            </button>
          </div>
        )}

        {procData && dataIsFetched && !deploymentPayload ? (
          <div className="stepThreeActions mx-auto w-[60%]">
            <button
              className="flex flex-row items-center mx-auto bg-yellow-400 hover:bg-yellow-600 py-4"
              onClick={() => buildAssociations()}
            >
              <IoNuclear className="text-2xl text-black mx-2" />
              BUILD ASSOCIATIONS
              <IoNuclear className="text-2xl text-black mx-2" />
            </button>
          </div>
        ) : null}
        {deploymentPayload && deploymentPayload.length ? (
          <div className="stepThreeActions mx-auto w-[60%]">
            <button
              className="flex flex-row items-center mx-auto bg-red-500 hover:bg-red-700 py-4"
              onClick={() => buildAssociations()}
            >
              <GiMushroomCloud className="text-2xl text-black mx-2" />
              DEPLOY ASSOCIATIONS
              <GiMushroomCloud className="text-2xl text-black mx-2" />
            </button>
          </div>
        ) : null}
        {deploymentPayload && deploymentPayload.length ? (
          <div className="preDeploymentLog w-full">
            <div className="flex flex-row">
              <h2 className="w-[50%] text-center text-2xl">
                Associated: {associatedCount}
              </h2>
              <h2 className="w-[50%] text-center text-2xl">
                Unassociated: {unassociatedCount}
              </h2>
            </div>
            <div className="flex flex-col">{renderedPayload}</div>
          </div>
        ) : null}
      </div>
    );
  }

  if (mode === "main") {
    return (
      <div className="ASINBase w-[50%] mx-auto">
        <div className="searchbar flex flex-row">
          <input type="text" className="w-[90%] border border-black pl-5" />
          <button className="w-[10%] bg-blue-500 flex items-center justify-center box-border ml-3 py-4">
            <FaSearch className="text-white" />
          </button>
        </div>
        <div className="actionBar flex flex-row my-3 justify-between">
          <button
            className="w-[32%] bg-green-500 py-2 hover:bg-green-700"
            onClick={() => setMode("associate")}
          >
            Associate One
          </button>
          <button
            className="w-[32%] bg-yellow-500 py-2 hover:bg-yellow-700"
            onClick={() => setMode("bulk")}
          >
            Associate Bulk
          </button>
          <button
            className="w-[32%] bg-red-500 py-2 hover:bg-red-700"
            onClick={() => setMode("delete")}
          >
            Delete All
          </button>
        </div>
        <div className="listContainer flex flex-col ">{renderedAsins}</div>
      </div>
    );
  }
};

/* Export Statement */
export default ASINBase;
