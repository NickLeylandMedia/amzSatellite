/* Library Imports */
//React
import React, { useEffect, useState } from "react";

/* Stylesheet Imports */

/* Image Imports */
import { IoMdClose } from "react-icons/io";

/* Component Imports */

/* Module Imports */
import papa from "papaparse";
import { bulkPushToShipment, pushToShipment } from "@/modules/api/shipments";

/* Component Interfaces */
interface Props {
  closer: any;
  shipmentID: number;
  refetch: any;
}

/* Component */
const SKUForm: React.FC<Props> = ({ closer, shipmentID, refetch }) => {
  /* State Variables */
  const [mode, setMode] = useState<"single" | "bulk">("single");
  const [file, setFile] = useState<File | null>(null);
  const [sku, setSku] = useState<any>("");
  const [qty, setQty] = useState<any>(null);
  const [importList, setImportList] = useState<any[] | null>(null);
  const [finalImport, setFinalImport] = useState<any[] | null>(null);
  const [successfulSingle, setSuccessfulSingle] = useState<boolean>(false);
  const [successfulBulk, setSuccessfulBulk] = useState<boolean>(false);
  /* End State Variables */

  /* Render Variables */
  let renderedImportList: any = <p className="">No items in list!</p>;
  /* End Render Variables */

  /* Render Logic */
  if (importList && importList.length > 0) {
    renderedImportList = importList.map((item) => {
      return (
        <div className="border border-black p-2 flex flex-row justify-evenly my-2">
          <h2 className="text-xl w-[30%] text-center">SKU: {item.sku}</h2>
          <p className="text-xl w-[30%] text-center">Quantity: {item.qty}</p>
        </div>
      );
    });
  }
  /* End Render Logic */

  /* Functions */
  function parseImportList() {
    if (!file) return alert("No file selected!");

    if (file) {
      papa.parse(file, {
        header: true,
        complete: (results) => {
          setImportList(results.data);
        },
      });
    }
  }

  async function submitSingle() {
    try {
      if (!sku || !qty || sku.length < 1 || qty.length == 0)
        return alert("Please fill out all fields!");

      if (sku && qty) {
        const response = await pushToShipment(shipmentID, sku, qty);
        if (response && response.message) {
          setSuccessfulSingle(true);
          setSku("");
          setQty("");
          refetch();
        }
        setTimeout(() => {
          setSuccessfulSingle(false);
        }, 3000);
      }
    } catch (error) {
      console.error(error);
    }
  }

  async function submitBulk() {
    if (finalImport && finalImport.length > 0) {
      const response = await bulkPushToShipment(finalImport);
      if (response && response.message) {
        setSuccessfulBulk(true);
        refetch();
      }
      setTimeout(() => {
        setImportList([]);
        setFinalImport([]);
        setFile(null);
        setSuccessfulBulk(false);
      }, 3000);
    }
  }
  /* End Functions */

  /* Effects */
  useEffect(() => {
    if (importList && importList.length > 0) {
      const formattedImport = importList.map((item) => {
        return {
          shipment_id: shipmentID,
          sku: item.sku || item.SKU,
          quantity: item.qty || item.Quantity || item.quantity,
        };
      });
      console.log(formattedImport);
      setFinalImport(formattedImport);
    }
  }, [importList]);

  /* End Effects */

  /* Component Return Statement */
  return (
    <div
      className={`skuForm w-[60%] border-4 border-black absolute top-[20%] left-[20%] min-h-[250px] bg-white p-[30px] rounded-xl`}
    >
      <IoMdClose
        className="absolute top-10 right-10 text-4xl hover:text-red-600 cursor-pointer"
        onClick={() => closer(false)}
      />
      {mode === "single" ? (
        <>
          <div className="modeselector w-1/3 mx-auto flex flex-row justify-center border border-black">
            <button className="w-[50%] py-3 text-center bg-blue-400">
              SINGLE
            </button>
            <button
              className="w-[50%] py-3 text-center"
              onClick={() => setMode("bulk")}
            >
              BULK
            </button>
          </div>
          <form action="" className="w-[60%] my-8 mx-auto">
            <div className="flex flex-col my-4">
              <label htmlFor="sku">SKU</label>
              <input
                type="text"
                name="sku"
                id="sku"
                value={sku}
                className="border border-black"
                onChange={(e) => setSku(e.target.value)}
              />
            </div>
            <div className="flex flex-col my-4">
              <label htmlFor="sku">Quantity</label>
              <input
                type="text"
                name="qty"
                value={qty}
                id=""
                className="border border-black"
                onChange={(e) => setQty(parseInt(e.target.value))}
              />
            </div>
            <div className="formActions mx-auto w-[70%] flex flex-row justify-center">
              <button
                className="bg-green-400 hover:bg-green-600 py-4 w-[150px] mx-1"
                onClick={(e) => {
                  e.preventDefault();
                  submitSingle();
                }}
              >
                ADD
              </button>
              <button
                className="bg-red-400 hover:bg-red-600 py-4 w-[150px] mx-1"
                onClick={(e) => {
                  e.preventDefault();
                  setSku("");
                  setQty("");
                  closer(false);
                }}
              >
                CANCEL
              </button>
            </div>
            {successfulSingle ? (
              <p className="text-green-500 text-xl text-center mt-4">
                Sku successfully added!
              </p>
            ) : (
              <p className="text-white text-xl text-center mt-4">
                Sku successfully added!
              </p>
            )}
          </form>
        </>
      ) : (
        <>
          <div className="modeselector w-1/3 mx-auto flex flex-row justify-center border border-black">
            <button
              className="w-[50%] py-3 text-center"
              onClick={() => setMode("single")}
            >
              SINGLE
            </button>
            <button className="w-[50%] py-3 text-center bg-blue-400">
              BULK
            </button>
          </div>
          {importList && importList.length > 0 ? (
            <>
              <div className="importList flex flex-col w-[60%] my-5 mx-auto">
                {renderedImportList}
              </div>
              <div className="importListActions w-[60%] my-5 mx-auto flex flex-row justify-center">
                <button
                  className="bg-green-400 hover:bg-green-600 py-4 w-[200px] mx-1"
                  onClick={(e) => {
                    e.preventDefault();
                    submitBulk();
                  }}
                >
                  ADD TO SHIPMENT
                </button>
                <button
                  className="bg-red-400 hover:bg-red-600 py-4 w-[200px] mx-1"
                  onClick={(e) => {
                    e.preventDefault();
                    setImportList([]);
                    setFinalImport([]);
                    setFile(null);
                  }}
                >
                  CANCEL
                </button>
              </div>
              {successfulBulk ? (
                <p className="text-green-500 text-xl text-center mt-4">
                  Bulk skus successfully added!
                </p>
              ) : (
                <p className="text-white text-xl text-center mt-4">
                  Bulk skus successfully added!
                </p>
              )}
            </>
          ) : (
            <form action="" className="w-[60%] my-8 mx-auto">
              <div className="flex flex-col my-4">
                <label htmlFor="file" className="text-center text-2xl mb-3">
                  Import CSV
                </label>
                <input
                  type="file"
                  name="file"
                  id=""
                  className="border border-black"
                  onChange={(e: any) => setFile(e.target.files[0])}
                />
              </div>
              <div className="formActions mx-auto w-[70%] flex flex-row justify-center">
                {file ? (
                  <button
                    className="bg-green-400 hover:bg-green-600 py-4 w-[150px] mx-1"
                    onClick={(e) => {
                      e.preventDefault();
                      parseImportList();
                    }}
                  >
                    PARSE
                  </button>
                ) : (
                  <button className="bg-gray-400 py-4 w-[150px] mx-1">
                    PARSE
                  </button>
                )}
                <button
                  className="bg-red-400 hover:bg-red-600 py-4 w-[150px] mx-1"
                  onClick={(e) => {
                    e.preventDefault();
                    setFile(null);
                  }}
                >
                  CANCEL
                </button>
              </div>
            </form>
          )}
        </>
      )}
    </div>
  );
};

/* Export Statement */
export default SKUForm;
